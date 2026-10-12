<div class="form-page">
  <div class="form-card">
    <h2><i class="bi bi-shield-lock"></i> Administration <span class="accent">Apér'o</span></h2>

    <form action="connexion_exe.php" method="POST">
      <div class="form-group">
        <label for="login">Login</label>
        <input type="text" id="login" name="login" placeholder="votre login" required>
      </div>
      <div class="form-group">
        <label for="mdp">Mot de passe</label>
        <input type="password" id="mdp" name="mdp" placeholder="••••••••" required>
      </div>
      <div id="messageErreur" class="erreur"></div>
      <button type="submit" class="btn-apero">
        <i class="bi bi-box-arrow-in-right"></i> Se connecter
      </button>
    </form>
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
