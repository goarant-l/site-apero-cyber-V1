<div class="container m-auto text-center">

  <?php require "./info.BDD.php"; ?>

  <!-- Message d'information (connexion ou déconnexion) -->
  <?php if (isset($_GET['info'])): ?>
    <div class="alerte alerte-success" style="margin:1rem auto;max-width:600px;">
      <?php echo htmlspecialchars($_GET['info']); ?>
    </div>
  <?php endif; ?>

  <!-- Hero -->
  <div class="hero">
    <h1>Apér'o</h1>
    <p>Bières artisanales &amp; produits du terroir - l'art de l'apéritif à la française</p>
  </div>

  <!-- Grille produits -->
  <div class="products-section">
    <h2 style="border-bottom:3px solid #F4A424;padding-bottom:.4rem;">Nos produits</h2>

    <?php
    $requeteSQL = "SELECT numProduit, nomProduit, descriptif, prixUnitaire, nomImage FROM produit ORDER BY idCategorie";
    $reponse = $pdo->query($requeteSQL);
    $liste_produit = $reponse->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="products-grid">
      <?php foreach ($liste_produit as $produit): ?>
        <div class="card-produit">
          <!-- Lien sur l'image pour aller vers la fiche produit -->
          <a href="index.php?page=detail_produit&idProduit=<?php echo $produit['numProduit']; ?>">
            <img src="/image/<?php echo htmlspecialchars($produit['nomImage']); ?>"
              alt="<?php echo htmlspecialchars($produit['nomProduit']); ?>">
          </a>

          <div class="card-body">
            <h3 class="card-title"><?php echo htmlspecialchars($produit['nomProduit']); ?></h3>
            <p class="card-desc"><?php echo htmlspecialchars($produit['descriptif']); ?></p>
          </div>

          <!-- Modification du footer de la carte pour ajouter le bouton "Voir" -->
          <div class="card-footer" style="display: flex; justify-content: space-between; align-items: center;">
            <span><?php echo number_format($produit['prixUnitaire'], 2, ',', ' '); ?> €</span>
            <a href="index.php?page=detail_produit&idProduit=<?php echo $produit['numProduit']; ?>" class="btn-nav"
              style="font-size: 0.8rem; padding: 0.3rem 0.8rem; background: transparent; color: var(--ambre); text-decoration: none;">
              Voir <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Panier -->
  <div class="panier-wrapper">
    <a href="index.php?page=details_panier" class="btn-apero">
      <i class="bi bi-cart3"></i>&nbsp; Mon panier
    </a>
  </div>
</div>