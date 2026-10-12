<?php
if (!isset($_SESSION['user'])) {
    header("location:index.php?page=connexion&erreur=" . urlencode("Vous devez être authentifié pour accéder à l'administration."));
    exit();
}

require "./info.BDD.php";

$requeteSQL = "SELECT codeUser, login, nom, prenom, role FROM users ORDER BY nom";
$reponse = $pdo->query($requeteSQL);
$liste_utilisateurs = $reponse->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-section">
  <h2><i class="bi bi-person-fill-gear"></i> Administration</h2>

  <div class="welcome-badge">
    👋 Bienvenue, <?php echo htmlspecialchars($_SESSION['user']); ?>
    &nbsp;—&nbsp; Rôle :
    <?php echo htmlspecialchars($_SESSION['roleUser'] ?? '—'); ?>
  </div>

  <table class="table-apero">
    <thead>
      <tr>
        <th>Login</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Rôle</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($liste_utilisateurs as $user): ?>
        <tr>
          <td><?php echo htmlspecialchars($user['login']); ?></td>
          <td><?php echo htmlspecialchars($user['nom']); ?></td>
          <td><?php echo htmlspecialchars($user['prenom']); ?></td>
          <td>
            <?php
              $role = $user['role'];
              $label = $role === 'adm' ? 'Admin' : 'Utilisateur';
              echo htmlspecialchars($label);
            ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
