<?php
require_once __DIR__ . '/vendor/autoload.php';
// require_once __DIR__ . '/gestion_secrets.php';
// require_once __DIR__ . '/info.BDD.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=inscription_client");
    exit();
}

/**
 * Valide l'adresse e-mail et le numéro de téléphone.
 */
function verifierCoordonnees(string $mail, string $telephone): array {
    $erreurs = [];

    if ($mail === '' || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "L'adresse e-mail n'est pas valide.";
    }

    $telNettoye = preg_replace('/[\s.\-]/', '', $telephone);
    if ($telNettoye === '' || !preg_match('/^(?:(?:\+|00)33|0)[1-9]\d{8}$/', $telNettoye)) {
        $erreurs[] = "Le numéro de téléphone n'est pas valide (ex: 0612345678).";
    }

    return $erreurs;
}

/**
 * Vérifie la validité du mot de passe (min 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial).
 */
function verifierMotDePasse(string $mdp): array {
    $erreurs = [];

    if (strlen($mdp) < 8) {
        $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }
    if (!preg_match('/[A-Z]/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins une majuscule.";
    }
    if (!preg_match('/[a-z]/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins une minuscule.";
    }
    if (!preg_match('/\d/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins un chiffre.";
    }
    if (!preg_match('/[^a-zA-Z\d]/', $mdp)) {
        $erreurs[] = "Le mot de passe doit contenir au moins un caractère spécial.";
    }

    return $erreurs;
}

function redirigerErreur(string $message): void {
    header("Location: index.php?page=inscription_client&erreur=" . urlencode($message));
    exit();
}

require "./info.BDD.php";
require_once "./gestion_secrets.php";


// Hacher le mot de passe avant l'insertion
// $hashMdp = hasher_mdp($mdp);

// 1. Récupération et assainissement des données
$nom        = trim($_POST['nom'] ?? '');
$prenom     = trim($_POST['prenom'] ?? '');
$pseudo     = trim($_POST['pseudo'] ?? '');
$mail       = trim($_POST['mail'] ?? '');
$telephone  = trim($_POST['telephone'] ?? '');
$rue        = trim($_POST['rue'] ?? '');
$codePostal = trim($_POST['codePostal'] ?? '');
$ville      = trim($_POST['ville'] ?? '');
$mdp        = $_POST['mdp'] ?? '';

$erreurs = [];

if (
    $nom === '' || $prenom === '' || $pseudo === '' ||
    $mail === '' || $telephone === '' || $rue === '' ||
    $codePostal === '' || $ville === '' || $mdp === ''
) {
    $erreurs[] = "Tous les champs sont obligatoires.";
}

if (mb_strlen($nom) > 32 || mb_strlen($prenom) > 32 || mb_strlen($pseudo) > 32) {
    $erreurs[] = "Le nom, le prénom ou le pseudo ne doit pas dépasser 32 caractères.";
}

if (!preg_match('/^\d{5}$/', $codePostal)) {
    $erreurs[] = "Le code postal doit contenir exactement 5 chiffres.";
}

// 2. Validation des coordonnées et du mot de passe
$erreurs = array_merge($erreurs, verifierCoordonnees($mail, $telephone));
$erreurs = array_merge($erreurs, verifierMotDePasse($mdp));

if (!empty($erreurs)) {
    redirigerErreur(implode(' ', $erreurs));
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 3. Vérifier si le pseudo est déjà pris
    $reqVerif = $pdo->prepare("SELECT pseudo FROM client WHERE pseudo = :pseudo");
    $reqVerif->execute(['pseudo' => $pseudo]);

    if ($reqVerif->fetch()) {
        redirigerErreur("Ce pseudo est déjà utilisé, veuillez en choisir un autre.");
    }

    // 4. Hachage du mot de passe et chiffrement des données personnelles
    $hashMdp          = hasher_mdp($mdp);
    $mailChiffre      = chiffrer_donnee($mail, $encryptionKey);
    $telephoneChiffre = chiffrer_donnee($telephone, $encryptionKey);
    $rueChiffree      = chiffrer_donnee($rue, $encryptionKey);
    $codePostalChiffre = chiffrer_donnee($codePostal, $encryptionKey);
    $villeChiffree    = chiffrer_donnee($ville, $encryptionKey);

    $pdo->beginTransaction();

    // 5. Insertion dans client
    $insertClient = $pdo->prepare("
        INSERT INTO client (nom, prenom, pseudo, mdp, mail, telephone)
        VALUES (:nom, :prenom, :pseudo, :mdp, :mail, :telephone)
    ");
    $insertClient->execute([
        'nom'       => $nom,
        'prenom'    => $prenom,
        'pseudo'    => $pseudo,
        'mdp'       => $hashMdp,
        'mail'      => $mailChiffre,
        'telephone' => $telephoneChiffre
    ]);
    $idClient = (int) $pdo->lastInsertId();

    // 6. Insertion dans adresse
    $insertAdresse = $pdo->prepare("
        INSERT INTO adresse (rue, codePostal, ville)
        VALUES (:rue, :codePostal, :ville)
    ");
    $insertAdresse->execute([
        'rue'        => $rueChiffree,
        'codePostal' => $codePostalChiffree,
        'ville'      => $villeChiffree
    ]);
    $idAdresse = (int) $pdo->lastInsertId();

    // 7. Liaison dans habiter
    $insertHabiter = $pdo->prepare("
        INSERT INTO habiter (idAdresse, idClient)
        VALUES (:idAdresse, :idClient)
    ");
    $insertHabiter->execute([
        'idAdresse' => $idAdresse,
        'idClient'  => $idClient
    ]);

    $pdo->commit();

    $message = "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter.";
    header("Location: index.php?page=connexion_client&info=" . urlencode($message));
    exit();

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if ($e->getCode() === '23000') {
        redirigerErreur("Ce pseudo est déjà utilisé, veuillez en choisir un autre.");
    }

    redirigerErreur("Une erreur est survenue lors de l'inscription.");
}