<?php
session_save_path(__DIR__ . '/tmp');
session_start();

// Vérifier que le client est connecté
if (!isset($_SESSION['client_user'])) {
    header("location:index.php?page=connexion_client");
    exit();
}

$idCommande = $_GET['idCommande'] ?? '';
$idProduit = $_GET['idProduit'] ?? '';

if (empty($idCommande) || empty($idProduit)) {
    header("location:index.php?page=detail_panier&erreur=" . urlencode("Paramètres manquants pour la suppression."));
    exit();
}

require "./info.BDD.php";

try {
    $statement = $pdo->prepare("DELETE FROM contenir WHERE noCommande = :noCommande AND numProduit = :numProduit");
    $resultat = $statement->execute([
        'noCommande' => $idCommande,
        'numProduit' => $idProduit
    ]);

    if ($resultat) {
        header("location:index.php?page=details_panier&info=" . urlencode("L'article a été supprimé de votre panier."));
    } else {
        header("location:index.php?pagedetails_panier&erreur=" . urlencode("Problème lors de la suppression de l'article."));
    }
    exit();

} catch (PDOException $e) {
    header("location:index.php?page=details_panier&erreur=" . urlencode("Erreur SQL : " . $e->getMessage()));
    exit();
}
?>