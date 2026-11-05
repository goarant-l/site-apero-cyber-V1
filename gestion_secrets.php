<?php 
// Chargement de l'autoload de Composer
require_once __DIR__ . '/vendor/autoload.php';

// Chargement du fichier .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Chargement des variables d'environnement
$hashKey = getenv('HASH_KEY');
$encryptionKey = getenv('ENCRYPTION_KEY');

function hasher_mdp (string $mdp): string {
	return password_hash($mdp, PASSWORD_DEFAULT);
}

function verifier_mdp (string $mdp, string $hash): bool {
	return password_verify($mdp, $hash);
}

function chiffrer_donnee (string $donnee, string $cle_chiffrement): string {
      $methode = 'AES-256-CBC';
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($methode));
        $donnee_chiffree = openssl_encrypt($donnee, $methode, $cle_chiffrement, 0, $iv);
        return base64_encode($iv . $donnee_chiffree);
}

function dechiffrer_donnee (string $donnee_chiffree, string $cle_chiffrement): string {
    $methode = 'AES-256-CBC';
    $donnee_chiffree = base64_decode($donnee_chiffree);
    $iv_length = openssl_cipher_iv_length($methode);
    $iv = substr($donnee_chiffree, 0, $iv_length);
    $donnee_chiffree = substr($donnee_chiffree, $iv_length);
    return openssl_decrypt($donnee_chiffree, $methode, $cle_chiffrement, 0, $iv);
}

?>