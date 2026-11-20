<?php

$login = $_POST['login'] ?? '';
$mdp   = $_POST['mdp']   ?? '';

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/gestion_secrets.php';
require "./info.BDD.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=connexion");
    exit();
}

// Assainissement et validation côté serveur
$login = trim($login);

if ($login === '' || $mdp === '') {
    header("Location: index.php?page=connexion&erreur=" . urlencode("Veuillez saisir votre login et votre mot de passe."));
    exit();
}

if (mb_strlen($login) > 15) {
    header("Location: index.php?page=connexion&erreur=" . urlencode("Identifiant ou mot de passe incorrect."));
    exit();
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Requête préparée pour éviter les injections SQL
// Récupération par login uniquement
$requeteSQL = "SELECT login, mdp, nom, prenom, role FROM users WHERE login = :login";
$stmt = $pdo->prepare($requeteSQL);
$stmt->execute(['login' => $login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

//Vérification du mot de passe haché avec verifier_mdp()
if ($user === false || !verifier_mdp($mdp, $user['mdp'])) {
    header("location:index.php?page=connexion&erreur=" . urlencode("Identifiant ou mot de passe incorrect."));
    exit();
} 

    session_regenerate_id(true);
    $_SESSION['user'] = $user['login'];
    $_SESSION['roleUser'] = $user['role'];

    header("location:index.php?page=admin");
    exit();
    
} catch (PDOException $e) {
    header("Location: index.php?page=connexion&erreur=" . urlencode("Erreur technique lors de la connexion."));
    exit();
}

?>