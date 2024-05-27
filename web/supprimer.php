<?php
// Vérifier si le type et l'ID sont définis dans l'URL
if (isset($_GET["type"]) && isset($_GET["id"])) {
    // Inclure le fichier de configuration
    require_once('config.php');

    // Connexion à la base de données
    $connexion = new mysqli(SERVEUR, UTILISATEUR, MOT_DE_PASSE, BASE_DE_DONNEES);

    // Vérifier la connexion
    if ($connexion->connect_error) {
        die("Échec de la connexion : " . $connexion->connect_error);
    }

    // Échapper les données pour éviter les injections SQL
    $type = $connexion->real_escape_string($_GET["type"]);
    $id = intval($_GET["id"]); // Convertir en entier pour des raisons de sécurité

    // Préparer la requête de suppression
    $delete_query = "";

    // Vérifier le type de données et construire la requête de suppression correspondante
    if ($type === 'dht22_ext') {
        $delete_query = "DELETE FROM dht22_ext WHERE de_pk_id = ?";
    } elseif ($type === 'dht22_int') {
        $delete_query = "DELETE FROM dht22_int WHERE di_pk_id = ?";
    } elseif ($type === 'hx711') {
        $delete_query = "DELETE FROM hx711 WHERE h_pk_id = ?";
    } elseif ($type === 'images_stand') {
        $delete_query = "DELETE FROM images_stand WHERE is_pk_id = ?";
    } elseif ($type === 'images_infra') {
        $delete_query = "DELETE FROM images_infra WHERE ii_pk_id = ?";
    } else {
        echo "Type de données non pris en charge.";
        exit; // Arrêter le script si le type de données n'est pas pris en charge
    }

    // Préparation de la requête de suppression et liaison des paramètres
    $stmt_delete = $connexion->prepare($delete_query);
    $stmt_delete->bind_param("i", $id);

    // Exécution de la requête de suppression
    if ($stmt_delete->execute()) {
        echo "success";
    } else {
        echo "Erreur lors de la suppression de la donnée : " . $connexion->error;
    }

    // Fermer la connexion à la base de données
    $connexion->close();
} else {
    // Si le type ou l'ID n'est pas défini, renvoyer un message d'erreur
    echo "Paramètres manquants pour la suppression.";
}
