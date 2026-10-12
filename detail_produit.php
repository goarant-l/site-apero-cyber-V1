<?php
// On récupère l'identifiant du produit passé dans l'URL
$idProduit = $_GET["idProduit"] ?? "";

if (empty($idProduit)) {
    echo "<div class='alerte alerte-danger'>Produit introuvable.</div>";
    exit;
}

require "./info.BDD.php";

// Requête pour récupérer les caractéristiques du produit
$requeteSQL = "SELECT numProduit, nomProduit, descriptif, prixUnitaire, nomImage FROM produit WHERE numProduit = :id";
$stmt = $pdo->prepare($requeteSQL);
$stmt->execute(['id' => $idProduit]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo "<div class='alerte alerte-danger'>Ce produit n'existe pas.</div>";
    exit;
}
?>

<div class="products-section" style="max-width: 900px; margin: 2rem auto; padding: 0 1rem;">

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

    <div
        style="background: var(--blanc); border-radius: 8px; box-shadow: var(--ombre); padding: 2rem; display: flex; gap: 2.5rem; flex-wrap: wrap;">

        <!-- Image du produit -->
        <div style="flex: 1; min-width: 300px; display: flex; align-items: center; justify-content: center;">
            <img src="/image/<?php echo htmlspecialchars($produit['nomImage']); ?>"
                alt="<?php echo htmlspecialchars($produit['nomProduit']); ?>"
                style="max-width: 100%; height: auto; border-radius: 8px;">
        </div>

        <!-- Détails et formulaire d'ajout -->
        <div style="flex: 1; min-width: 300px; display: flex; flex-direction: column; justify-content: center;">
            <h2 style="color: var(--marine); font-size: 2.2rem; margin-bottom: 1rem;">
                <?php echo htmlspecialchars($produit['nomProduit']); ?>
            </h2>
            <p style="color: var(--gris); margin-bottom: 1.5rem; font-size: 1.1rem; line-height: 1.6;">
                <?php echo htmlspecialchars($produit['descriptif']); ?>
            </p>
            <p style="font-size: 1.8rem; font-weight: bold; color: var(--ambre); margin-bottom: 2rem;">
                <?php echo number_format($produit['prixUnitaire'], 2, ',', ' '); ?> €
            </p>

            <!-- Le formulaire d'ajout au panier comme indiqué dans le TP16 -->
            <form action="ajout_panier_exe.php" method="POST"
                style="background: var(--creme); padding: 1.5rem; border-radius: 8px; border: 1px solid #e8dcc4;">
                <input type="hidden" name="idProduit" value="<?php echo htmlspecialchars($idProduit); ?>" />

                <div class="form-group" style="margin-bottom: 1.5rem; display: flex; align-items: center;">
                    <label for="quantite"
                        style="font-weight: bold; color: var(--marine); font-size: 1.1rem; margin-right: 15px; margin-bottom: 0;">Quantité</label>
                    <input type="number" id="quantite" name="quantite" value="1" min="1"
                        style="width: 80px; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; font-size: 1.1rem;">
                </div>

                <button type="submit" class="btn-apero" style="width: 100%; font-size: 1.1rem; padding: 0.8rem;">
                    <i class="bi bi-basket me-2"></i> Ajouter au panier
                </button>
            </form>

            <div style="margin-top: 1.5rem; text-align: center;">
                <a href="index.php?page=detail_panier" class="btn-apero btn-apero-outline"
                    style="width: 100%; font-size: 1.1rem; padding: 0.8rem;">
                    <i class="bi bi-cart4"></i> Voir mon panier
                </a>
            </div>

        </div>
    </div>
</div>