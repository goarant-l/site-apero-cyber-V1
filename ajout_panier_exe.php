<?php
session_save_path(__DIR__ . '/tmp');
session_start();

$quantiteParam = $_POST['quantite'] ?? '';
$idProduitParam = $_POST['idProduit'] ?? '';

// Vérifier que le client est bien connecté avant d'ajouter au panier
if (!isset($_SESSION['client_user'])) {
    header("location:index.php?page=connexion_client&info=" . urlencode("Connexion requise avant de commander."));
    exit();
}

require "./info.BDD.php";

try {
    // 1. Récupérer le vrai ID du client
    // On sélectionne tout (*) pour éviter une erreur si la colonne s'appelle 'id' au lieu de 'idClient'
    $stmtClient = $pdo->prepare("SELECT * FROM client WHERE pseudo = :pseudo");
    $stmtClient->execute(['pseudo' => $_SESSION['client_user']]);
    $clientInfo = $stmtClient->fetch(PDO::FETCH_ASSOC);

    // Fallback : On essaie de trouver idClient, sinon id, sinon on garde le pseudo.
    $idClient = $_SESSION['client_user'];
    if ($clientInfo) {
        if (isset($clientInfo['idClient']))
            $idClient = $clientInfo['idClient'];
        elseif (isset($clientInfo['id']))
            $idClient = $clientInfo['id'];
    }

    // 2. Chercher si le client a déjà une commande 'En cours' (ENC)
    $reqRecherche = "SELECT noCommande FROM commande WHERE idClient = :idClient AND idStatut = 'ENC'";
    $stmtRecherche = $pdo->prepare($reqRecherche);
    $stmtRecherche->execute(['idClient' => $idClient]);
    $commandeEnCours = $stmtRecherche->fetch(PDO::FETCH_ASSOC);

    if (!$commandeEnCours) {
        // 3. Si aucune commande n'est trouvée, on en crée une nouvelle
        $reqInsertCmd = "INSERT INTO commande (idClient, idStatut, dateCommande) VALUES (:idClient, 'ENC', NOW())";
        $stmtInsertCmd = $pdo->prepare($reqInsertCmd);
        $stmtInsertCmd->execute(['idClient' => $idClient]);

        // On récupère le numéro de la nouvelle commande
        $noCommande = $pdo->lastInsertId();
    } else {
        $noCommande = $commandeEnCours['noCommande'];
    }

    // 4. Vérifier si l'article est DÉJÀ dans la commande
    $reqVerifArticle = "SELECT quantite FROM contenir WHERE noCommande = :noCmd AND numProduit = :idProd";
    $stmtVerif = $pdo->prepare($reqVerifArticle);
    $stmtVerif->execute([
        'noCmd' => $noCommande,
        'idProd' => $idProduitParam
    ]);
    $articleExistant = $stmtVerif->fetch(PDO::FETCH_ASSOC);

    if ($articleExistant) {
        // L'article est déjà là : on UPDATE la quantité
        $reqUpdate = "UPDATE contenir SET quantite = quantite + :qte WHERE noCommande = :noCmd AND numProduit = :idProd";
        $stmtUpdate = $pdo->prepare($reqUpdate);
        $stmtUpdate->execute([
            'qte' => $quantiteParam,
            'noCmd' => $noCommande,
            'idProd' => $idProduitParam
        ]);
    } else {
        // C'est un nouvel article : on INSERT
        $reqInsertContenir = "INSERT INTO contenir (noCommande, numProduit, quantite) VALUES (:noCommande, :numProduit, :quantite)";
        $stmtInsertContenir = $pdo->prepare($reqInsertContenir);
        $stmtInsertContenir->execute([
            'noCommande' => $noCommande,
            'numProduit' => $idProduitParam,
            'quantite' => $quantiteParam
        ]);
    }

    // Succès !
    header("location:index.php?page=detail_produit&idProduit=$idProduitParam&info=" . urlencode("L'article a bien été ajouté à votre panier !"));
    exit();

} catch (PDOException $e) {
    // Si la commande échoue, on affiche la VRAIE erreur envoyée par SQL pour comprendre le problème
    $erreurMessage = "Erreur SQL : " . $e->getMessage();
    header("location:index.php?page=detail_produit&idProduit=$idProduitParam&erreur=" . urlencode($erreurMessage));
    exit();
}
?>