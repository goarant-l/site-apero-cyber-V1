<div class="form-page">
  <div class="form-card">
    <h2><i class="bi bi-person-plus"></i> Créer un <span class="accent">compte</span></h2>

    <form action="inscription_client_exe.php" method="POST">
      <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" placeholder="Dupont" maxlength="32" required>
      </div>
      <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" placeholder="Jean" maxlength="32" required>
      </div>
      <div class="form-group">
        <label for="pseudo">Identifiant (pseudo)</label>
        <input type="text" id="pseudo" name="pseudo" placeholder="jean_dupont" maxlength="32" required>
      </div>
      <div class="form-group">
        <label for="mail">Adresse e-mail</label>
        <input type="email" id="mail" name="mail" placeholder="jean.dupont@exemple.fr" required>
      </div>
      <div class="form-group">
        <label for="telephone">Téléphone</label>
        <input type="tel" id="telephone" name="telephone" placeholder="0612345678" required>
      </div>
      <div class="form-group">
        <label for="rue">Rue</label>
        <input type="text" id="rue" name="rue" placeholder="12 rue des Lilas" maxlength="32" required>
      </div>
      <div class="form-group">
        <label for="codePostal">Code postal</label>
        <input type="text" id="codePostal" name="codePostal" placeholder="29000" pattern="[0-9]{5}" maxlength="5" required>
      </div>
      <div class="form-group">
        <label for="ville">Ville</label>
        <input type="text" id="ville" name="ville" placeholder="Quimper" maxlength="32" required>
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