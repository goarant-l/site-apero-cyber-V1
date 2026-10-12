<?php
if (!isset($_SESSION['client_user'])) {
    header("location:index.php?page=connexion_client&info=" . urlencode("Connectez-vous pour voir vos commandes."));
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

try {
    $statement = $pdo->prepare("
        SELECT commande.noCommande, 
               statut.libelleStatut, 
               commande.dateCommande, 
               SUM(contenir.quantite) AS nb_articles, 
               SUM(contenir.quantite * produit.prixUnitaire) AS montanttotal
        FROM commande
        INNER JOIN contenir ON commande.noCommande = contenir.noCommande
        INNER JOIN produit ON produit.numProduit = contenir.numProduit
        INNER JOIN statut ON commande.idStatut = statut.idStatut
        WHERE commande.idClient = :idClient
        GROUP BY commande.noCommande, statut.libelleStatut, commande.dateCommande
        ORDER BY commande.dateCommande DESC, commande.noCommande DESC
    ");
    $statement->execute(['idClient' => $idClient]);
    $liste_commandes = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<div class='alerte alerte-danger'>Erreur SQL : " . $e->getMessage() . "</div>";
    $liste_commandes = [];
}
?>

<div class="admin-section" style="max-width: 1000px;">
    <h2 style="text-align: center; border-bottom: none; margin-bottom: 2rem;">
        <i class="bi bi-box-seam"></i> Historique de mes commandes
    </h2>

    <?php if (isset($_GET['info'])): ?>
        <div class="alerte alerte-success">
            <?php echo htmlspecialchars($_GET['info']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($liste_commandes)): ?>
        <div
            style="text-align: center; padding: 3rem; background: var(--blanc); border-radius: 8px; box-shadow: var(--ombre);">
            <i class="bi bi-receipt" style="font-size: 3rem; color: var(--gris); margin-bottom: 1rem; display: block;"></i>
            <p style="font-size: 1.2rem; color: var(--marine);">Vous n'avez passé aucune commande pour le moment.</p>
            <a href="index.php?page=accueil" class="btn-apero" style="margin-top: 1rem;">Commencer mes achats</a>
        </div>
    <?php else: ?>
        <div
            style="overflow-x: auto; background: var(--blanc); border-radius: 8px; box-shadow: var(--ombre); padding: 1rem;">
            <table class="table-apero" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th style="text-align: center;">N° Commande</th>
                        <th>Date</th>
                        <th style="text-align: center;">Articles</th>
                        <th>Statut</th>
                        <th style="text-align: right;">Montant Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($liste_commandes as $commande): ?>
                        <tr>
                            <td style="text-align: center; font-weight: bold;">
                                <?php echo htmlspecialchars($commande['noCommande']); ?>
                            </td>
                            <td>
                                <?php echo date('d/m/Y H:i', strtotime($commande['dateCommande'])); ?>
                            </td>
                            <td style="text-align: center;">
                                <?php echo htmlspecialchars($commande['nb_articles']); ?>
                            </td>
                            <td>
                                <?php
                                $badgeColor = '#6c757d'; // gris
                                if ($commande['libelleStatut'] == 'Validée')
                                    $badgeColor = '#28a745'; // vert
                                if ($commande['libelleStatut'] == 'En cours')
                                    $badgeColor = '#F4A424'; // orange
                                if ($commande['libelleStatut'] == 'Payée')
                                    $badgeColor = '#17a2b8'; // cyan
                                if ($commande['libelleStatut'] == 'Envoyée')
                                    $badgeColor = '#007bff'; // bleu
                                if ($commande['libelleStatut'] == 'Annulée')
                                    $badgeColor = '#dc3545'; // rouge
                                ?>
                                <span
                                    style="background: <?php echo $badgeColor; ?>; color: white; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.85rem; font-weight: bold;">
                                    <?php echo htmlspecialchars($commande['libelleStatut']); ?>
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: bold; color: var(--ambre);">
                                <?php echo number_format($commande['montanttotal'], 2, ',', ' '); ?> €
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>