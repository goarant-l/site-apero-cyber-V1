<?php
session_save_path(__DIR__ . '/tmp');
session_start();

$pseudo = $_POST['pseudo'] ?? '';
$mdp    = $_POST['mdp']    ?? '';

require "./info.BDD.php";

// Requête préparée pour éviter les injections SQL
$requeteSQL = "SELECT pseudo, mdp, nom, prenom FROM client WHERE pseudo = :pseudo AND mdp = :mdp";
$stmt = $pdo->prepare($requeteSQL);
$stmt->execute(['pseudo' => $pseudo, 'mdp' => $mdp]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if ($client === false) {
    $message = "Identifiant ou mot de passe incorrect.";
    header("location:index.php?page=connexion_client&erreur=" . urlencode($message));
    exit();
} else {
    $_SESSION['client_user']   = $client['pseudo'];
    $_SESSION['client_nom']    = $client['nom'];
    $_SESSION['client_prenom'] = $client['prenom'];

    $message = "Ravi de vous revoir, " . $client['prenom'] . " !";
    header("location:index.php?page=accueil&info=" . urlencode($message));
    exit();
}
?>
