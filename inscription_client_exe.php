<?php
session_save_path(__DIR__ . '/tmp');
session_start();

$nom    = $_POST['nom']    ?? '';
$prenom = $_POST['prenom'] ?? '';
$pseudo = $_POST['pseudo'] ?? '';
$mdp    = $_POST['mdp']    ?? '';

require "./info.BDD.php";

// Vérifier si le pseudo est déjà pris (dans la table client)
$verifSQL = "SELECT pseudo FROM client WHERE pseudo = :pseudo";
$reqVerif = $pdo->prepare($verifSQL);
$reqVerif->execute(['pseudo' => $pseudo]);
$existant = $reqVerif->fetch();

if ($existant) {
    header("location:index.php?page=inscription_client&erreur=" . urlencode("Ce pseudo est déjà utilisé, veuillez en choisir un autre."));
    exit();
}

// Insertion du nouveau client
$insertSQL = "INSERT INTO client (nom, prenom, pseudo, mdp) VALUES (:nom, :prenom, :pseudo, :mdp)";
$reqInsert = $pdo->prepare($insertSQL);
$succes = $reqInsert->execute([
    'nom'    => $nom,
    'prenom' => $prenom,
    'pseudo' => $pseudo,
    'mdp'    => $mdp
]);

if ($succes) {
    $message = "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter.";
    header("location:index.php?page=connexion_client&info=" . urlencode($message));
    exit();
} else {
    header("location:index.php?page=inscription_client&erreur=" . urlencode("Une erreur est survenue lors de l'inscription."));
    exit();
}
?>
