<div class="form-page">
  <div class="form-card">
    <h2><i class="bi bi-person-circle"></i> Espace <span class="accent">Client</span></h2>

    <form action="connexion_client_exe.php" method="POST">
      <div class="form-group">
        <label for="pseudo">Identifiant</label>
        <input type="text" id="pseudo" name="pseudo" placeholder="votre pseudo" required>
      </div>
      <div class="form-group">
        <label for="mdp">Mot de passe</label>
        <input type="password" id="mdp" name="mdp" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn-apero">
        <i class="bi bi-box-arrow-in-right"></i> Se connecter
      </button>
    </form>

    <p class="link-small">
      Pas encore de compte ? <a href="index.php?page=inscription_client">S'inscrire ici</a>
    </p>
  </div>
</div>

<?php if (isset($_GET['erreur'])): ?>
  <div class="alerte alerte-danger">
    <?php echo htmlspecialchars($_GET['erreur']); ?>
  </div>
<?php endif; ?>

<?php if (isset($_GET['info'])): ?>
  <div class="alerte alerte-success">
    <?php echo htmlspecialchars($_GET['info']); ?>
  </div>
<?php endif; ?>
