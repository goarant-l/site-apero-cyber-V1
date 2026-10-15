<?php
// Connexion à la base de données
// $host     = 'localhost:8889'; // <-- Ajout du port 8889
$host = 'localhost';
$dbname   = 'site-apero-cyber';
$username = 'root';
$password = '';           // <-- Sur XAAMP, le mot de passe est 'root'
// $username = 'adminer';    <-- Sur PROMOX
// $password = 'Lolo123cyber';  <-- Sur PROMOX

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
