<?php

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



