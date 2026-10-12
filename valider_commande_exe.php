<?php
session_save_path(__DIR__ . '/tmp');
session_start();

if (!isset($_SESSION['client_user'])) {
    header("location:index.php?page=connexion_client");
    exit();
}

$idCommande = $_GET['idCommande'] ?? '';

if (empty($idCommande)) {
    header("location:index.php?page=detail_panier&erreur=" . urlencode("Aucune commande à valider."));
    exit();
}

require "./info.BDD.php";

// Récupérer l'ID du client pour la sécurité
$stmtClient = $pdo->prepare("SELECT * FROM client WHERE pseudo = :pseudo");
$stmtClient->execute(['pseudo' => $_SESSION['client_user']]);
$clientInfo = $stmtClient->fetch(PDO::FETCH_ASSOC);

$idClient = $_SESSION['client_user'];
if ($clientInfo) {
    if (isset($clientInfo['idClient']))
        $idClient = $clientInfo['idClient'];
    elseif (isset($clientInfo['id']))
        $idClient = $clientInfo['id'];
}

try {
    // Changement du statut 'ENC' (En cours) vers 'VAL' (Validée) et mise à jour de la date
    $statement = $pdo->prepare("
        UPDATE commande 
        SET idStatut = 'VAL', dateCommande = NOW() 
        WHERE idStatut = 'ENC' AND noCommande = :noCmd AND idClient = :idClient
    ");

    $resultat = $statement->execute([
        'noCmd' => $idCommande,
        'idClient' => $idClient
    ]);

    if ($resultat && $statement->rowCount() > 0) {
        header("location:index.php?page=historique_commande&info=" . urlencode("Votre commande a bien été validée ! Merci pour votre achat."));
    } else {
        header("location:index.php?page=details_panierr&erreur=" . urlencode("Impossible de valider cette commande (déjà validée ou inexistante)."));
    }
    exit();

} catch (PDOException $e) {
    header("location:index.php?page=details_panier&erreur=" . urlencode("Erreur SQL : " . $e->getMessage()));
    exit();
}
?>