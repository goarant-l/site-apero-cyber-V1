<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pseudo = $_POST['pseudo'] ?? '';
$mdp = $_POST['mdp']    ?? '';

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/gestion_secrets.php';
require "./info.BDD.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=connexion_client");
    exit();
}

//Assainissement et validation côté serveur
$pseudo = trim($pseudo);

if ($pseudo === '' || $mdp === '') {
    $message = "Veuillez saisir votre identifiant et votre mot de passe.";
    header("Location: index.php?page=connexion_client&erreur=" . urlencode($message));
    exit();
}

if (mb_strlen($pseudo) > 32) {
    $message = "Identifiant ou mot de passe incorrect.";
    header("Location: index.php?page=connexion_client&erreur=" . urlencode($message));
    exit();
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Requête préparée pour éviter les injections SQL
// Récupération du client uniquement par pseudo
    $requeteSQL = "SELECT pseudo, mdp, nom, prenom FROM client WHERE pseudo = :pseudo";
    $stmt = $pdo->prepare($requeteSQL);
    $stmt->execute(['pseudo' => $pseudo]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

//Vérification du mot de passe haché avec verifier_mdp()
if ($client === false || !verifier_mdp($mdp, $client['mdp'])) {
    $message = "Identifiant ou mot de passe incorrect.";
    header("location:index.php?page=connexion_client&erreur=" . urlencode($message));
    exit();
}
    session_regenerate_id(true);
    $_SESSION['client_user'] = $client['pseudo'];
    $_SESSION['client_nom'] = $client['nom'];
    $_SESSION['client_prenom'] = $client['prenom'];

    $message = "Ravi de vous revoir, " . $client['prenom'] . " !";
    header("location:index.php?page=accueil&info=" . urlencode($message));
    exit();

} catch (PDOException $e) {
    $message = "Erreur technique lors de la connexion.";
    header("Location: index.php?page=connexion_client&erreur=" . urlencode($message));
    exit();
}

?>