<?php
session_save_path(__DIR__ . '/tmp');
session_start();

$login = $_POST['login'] ?? '';
$mdp   = $_POST['mdp']   ?? '';

require "./info.BDD.php";

// Requête préparée pour éviter les injections SQL
$requeteSQL = "SELECT login, mdp, nom, prenom, role FROM users WHERE login = :login AND mdp = :mdp";
$stmt = $pdo->prepare($requeteSQL);
$stmt->execute(['login' => $login, 'mdp' => $mdp]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === false) {
    header("location:index.php?page=connexion&erreur=" . urlencode("Identifiant ou mot de passe incorrect."));
    exit();
} else {
    $_SESSION['user']     = $user['login'];
    $_SESSION['roleUser'] = $user['role'];
    header("location:index.php?page=admin");
    exit();
}
?>
