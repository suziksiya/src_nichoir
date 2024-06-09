<?php
// Reprend la session existante
session_start();

// Réinitialise le tableau de session
$_SESSION = array();

// Détruit la session actuelle
session_destroy();

// Redirige l'utilisateur vers la page de connexion
header("Location: login.php");

// Termine l'exécution du script
exit;
?>