<?php
session_save_path(__DIR__ . '/tmp');
session_start();

// Supprime toutes les variables de session
$_SESSION = [];

// Supprime le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

header("location:index.php?page=accueil&info=" . urlencode("Vous avez été déconnecté(e) avec succès."));
exit();
?>
