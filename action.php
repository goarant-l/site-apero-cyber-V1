<?php
session_save_path(__DIR__ . '/tmp');
session_start();

$login = $_POST["login"];
$mdp = $_POST["mdp"];

if ($login == "admin" && $mdp == "1234") {
    $_SESSION['user'] = $login;
    header("location:index.php?page=admin");
} else {
    header("location:index.php?page=connexion&&erreur=Probleme de connexion");
}
?>
<?php



