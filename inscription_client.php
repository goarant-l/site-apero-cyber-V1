<div class="form-page">
  <div class="form-card">
    <h2><i class="bi bi-person-plus"></i> Créer un <span class="accent">compte</span></h2>

    <form action="inscription_client_exe.php" method="POST">
      <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" placeholder="Dupont" required>
      </div>
      <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" placeholder="Jean" required>
      </div>
      <div class="form-group">
        <label for="pseudo">Identifiant (pseudo)</label>
        <input type="text" id="pseudo" name="pseudo" placeholder="jean_dupont" required>
      </div>
      <div class="form-group">
        <label for="mdp">Mot de passe</label>
        <input type="password" id="mdp" name="mdp" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn-apero">
        <i class="bi bi-person-check"></i> S'inscrire
      </button>
    </form>

    <p class="link-small">
      Déjà un compte ? <a href="index.php?page=connexion_client">Se connecter</a>
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
