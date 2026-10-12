<?php
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client_user'])) {
    header("location:index.php?page=connexion_client&info=" . urlencode("Connectez-vous pour voir votre panier."));
    exit();
}

require "./info.BDD.php";

// Récupérer l'ID du client
$stmtClient = $pdo->prepare("SELECT * FROM client WHERE pseudo = :pseudo");
$stmtClient->execute(['pseudo' => $_SESSION['client_user']]);
$clientInfo = $stmtClient->fetch(PDO::FETCH_ASSOC);

$idClient = $_SESSION['client_user'];
if ($clientInfo) {
    if (isset($clientInfo['idClient']))
        $idClient = $clientInfo['idClient'];
    elseif (isset($clientInfo['id']))
        $idClient = $clientInfo['id'];
}

// 1. Récupérer la liste des produits dans la commande 'En cours' (ENC)
try {
    $stmtProduits = $pdo->prepare("
        SELECT commande.noCommande, 
               produit.numProduit as idProduit, 
               produit.nomProduit, 
               produit.prixUnitaire, 
               produit.nomImage,
               contenir.quantite, 
               (produit.prixUnitaire * contenir.quantite) AS totalLigne
        FROM commande
        INNER JOIN contenir ON commande.noCommande = contenir.noCommande
        INNER JOIN produit ON produit.numProduit = contenir.numProduit
        WHERE commande.idStatut = 'ENC' AND commande.idClient = :idClient
    ");
    $stmtProduits->execute(['idClient' => $idClient]);
    $liste_produits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);

    // On récupère le noCommande s'il y a des produits
    $noCommande = !empty($liste_produits) ? $liste_produits[0]['noCommande'] : null;

} catch (PDOException $e) {
    echo "<div class='alerte alerte-danger'>Erreur SQL (Produits) : " . $e->getMessage() . "</div>";
    $liste_produits = [];
}

// 2. Calculer le montant total de la commande
$montantTotal = 0;
try {
    $stmtTotal = $pdo->prepare("
        SELECT SUM(produit.prixUnitaire * contenir.quantite) AS montantTotal
        FROM commande
        INNER JOIN contenir ON commande.noCommande = contenir.noCommande
        INNER JOIN produit ON produit.numProduit = contenir.numProduit
        WHERE commande.idStatut = 'ENC' AND commande.idClient = :idClient
    ");
    $stmtTotal->execute(['idClient' => $idClient]);
    $resultatTotal = $stmtTotal->fetch(PDO::FETCH_ASSOC);

    if ($resultatTotal && $resultatTotal['montantTotal']) {
        $montantTotal = $resultatTotal['montantTotal'];
    }
} catch (PDOException $e) {
    echo "<div class='alerte alerte-danger'>Erreur SQL (Total) : " . $e->getMessage() . "</div>";
}
?>

<div class="admin-section" style="max-width: 1000px;">
    <h2 style="text-align: center; border-bottom: none; margin-bottom: 2rem;">
        <i class="bi bi-cart3"></i> Mon panier
    </h2>

    <?php if (isset($_GET['info'])): ?>
        <div class="alerte alerte-success">
            <?php echo htmlspecialchars($_GET['info']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['erreur'])): ?>
        <div class="alerte alerte-danger">
            <?php echo htmlspecialchars($_GET['erreur']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($liste_produits)): ?>
        <div
            style="text-align: center; padding: 3rem; background: var(--blanc); border-radius: 8px; box-shadow: var(--ombre);">
            <i class="bi bi-basket3" style="font-size: 3rem; color: var(--gris); margin-bottom: 1rem; display: block;"></i>
            <p style="font-size: 1.2rem; color: var(--marine);">Votre panier est actuellement vide.</p>
            <a href="index.php?page=accueil" class="btn-apero" style="margin-top: 1rem;">Découvrir nos produits</a>
        </div>
    <?php else: ?>

        <div
            style="overflow-x: auto; background: var(--blanc); border-radius: 8px; box-shadow: var(--ombre); padding: 1rem;">
            <table class="table-apero" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th style="width: 80px;">Produit</th>
                        <th>Article</th>
                        <th style="text-align: right;">Prix unitaire</th>
                        <th style="text-align: center;">Quantité</th>
                        <th style="text-align: right;">Total</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($liste_produits as $article): ?>
                        <tr>
                            <td>
                                <img src="/image/<?php echo htmlspecialchars($article['nomImage']); ?>"
                                    alt="<?php echo htmlspecialchars($article['nomProduit']); ?>"
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            </td>
                            <td style="font-weight: bold;"><?php echo htmlspecialchars($article['nomProduit']); ?></td>
                            <td style="text-align: right;"><?php echo number_format($article['prixUnitaire'], 2, ',', ' '); ?> €
                            </td>
                            <td style="text-align: center;">
                                <span
                                    style="background: var(--creme); padding: 0.3rem 0.8rem; border-radius: 4px; font-weight: bold;">
                                    <?php echo htmlspecialchars($article['quantite']); ?>
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: bold; color: var(--ambre);">
                                <?php echo number_format($article['totalLigne'], 2, ',', ' '); ?> €
                            </td>
                            <td style="text-align: center;">
                                <a href="index.php?page=supprimer_panier_exe&idCommande=<?php echo $noCommande; ?>&idProduit=<?php echo $article['idProduit']; ?>"
                                    style="color: #dc3545; font-size: 1.2rem;"
                                    onclick="return confirm('Êtes-vous sûr de vouloir retirer cet article ?');">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div
            style="margin-top: 2rem; background: var(--blanc); border-radius: 8px; box-shadow: var(--ombre); padding: 2rem; text-align: center;">
            <h3 style="font-size: 1.8rem; margin-bottom: 1.5rem; color: var(--marine);">
                Montant total : <span style="color: var(--ambre);"><?php echo number_format($montantTotal, 2, ',', ' '); ?>
                    €</span>
            </h3>

            <a href="index.php?page=valider_commande_exe&idCommande=<?php echo $noCommande; ?>" class="btn-apero"
                style="font-size: 1.2rem; padding: 1rem 3rem;">
                <i class="bi bi-check-circle me-2"></i> Valider mon panier
            </a>
        </div>

    <?php endif; ?>
</div>