<?php
session_start();
$_SESSION = array(); // Vide toutes les variables de session

// Si vous souhaitez détruire complètement la session, aussi bien les données que l'identifiant de session, utilisez ceci:
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy(); // Détruit la session
header("Location: login.php"); // Redirige vers la page de connexion
exit;
?>