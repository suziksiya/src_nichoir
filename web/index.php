<?php
session_start();

// Inclure le fichier de configuration
require_once('config.php');

// Connexion à la base de données
$connexion = new mysqli(SERVEUR, UTILISATEUR, MOT_DE_PASSE, BASE_DE_DONNEES);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

$is_admin = false;

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Vérifier si l'utilisateur est admin
if (isset($_SESSION["username"]) && $_SESSION["username"] === "admin") {
    $is_admin = true;
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="icon" type="image/x-icon" href="nichoir.ico">
    <title>Page principale - Nichoir</title>
    <link rel="stylesheet" type="text/css" href="bdd.css">
</head>

<body>

    <div class="container">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
        <?php
        if ($is_admin) {
            echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <label for="u_sched">Changer la période d\'acquisition :</label>
            <select id="u_sched" name="u_sched">
                <option value="1">30 minutes</option>
                <option value="2">1 heure</option>
                <option value="3">2 heures</option>
            </select>
            <button type="submit">Changer</button>
          </form>';

            if (isset($_POST["u_sched"])) {
                $new_u_sched = $_POST["u_sched"];
                $update_query = "UPDATE users SET u_sched = ? WHERE u_username = ?";
                $stmt_update = $connexion->prepare($update_query);
                $stmt_update->bind_param("is", $new_u_sched, $_SESSION["username"]);
                $stmt_update->execute();
                echo "Période d'acquisition modifié avec succès !";
            }
        }
        ?>
        <p><a href="login.php">Se déconnecter</a></p>
    </div>

    <div class="container">
        <h1>Système de surveillance pour nid d'oiseau</h1>

        <?php
        // Connexion à la base de données
        $connexion = new mysqli(SERVEUR, UTILISATEUR, MOT_DE_PASSE, BASE_DE_DONNEES);

        // Vérifier la connexion
        if ($connexion->connect_error) {
            die("Échec de la connexion : " . $connexion->connect_error);
        }

        // Récupération des 5 dernières données du tableau dht22_ext avec les identifiants primaires
        $resultat_dht22_ext = $connexion->query("SELECT de_pk_id, de_date_heure, de_temperature, de_humidite FROM dht22_ext ORDER BY de_date_heure DESC LIMIT 5");
        if ($resultat_dht22_ext->num_rows > 0) {
            echo "<h2>Données du capteur DHT22 extérieur :</h2>";
            echo "<table border='1'>";
            echo "<tr>";
            if ($is_admin) {
                echo "<th>ID</th>";
                echo "<th>Supprimer</th>";
            }
            echo "<th>Date/Heure</th><th>Température</th><th>Humidité</th></tr>";
            while ($row = $resultat_dht22_ext->fetch_assoc()) {
                $temperature_ext = $row["de_temperature"] . " °C";
                $humidite_ext = $row["de_humidite"] . " %";
                echo "<tr>";
                if ($is_admin) {
                    echo "<td>" . $row["de_pk_id"] . "</td>";
                    echo "<td><a href='#' onclick='supprimerDonnee(\"dht22_ext\", " . $row["de_pk_id"] . ")'>Supprimer</a></td>";
                }
                echo "<td>" . $row["de_date_heure"] . "</td><td>" . $temperature_ext . "</td><td>" . $humidite_ext . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "Aucune donnée trouvée pour le capteur dht22_ext.";
        }

        // Récupération des 5 dernières données du tableau dht22_int avec les identifiants primaires
        $resultat_dht22_int = $connexion->query("SELECT di_pk_id, di_date_heure, di_temperature, di_humidite FROM dht22_int ORDER BY di_date_heure DESC LIMIT 5");
        if ($resultat_dht22_int->num_rows > 0) {
            echo "<h2>Données du capteur DHT22 intérieur :</h2>";
            echo "<table border='1'>";
            echo "<tr>";
            if ($is_admin) {
                echo "<th>ID</th>";
                echo "<th>Supprimer</th>";
            }
            echo "<th>Date/Heure</th><th>Température</th><th>Humidité</th></tr>";
            while ($row = $resultat_dht22_int->fetch_assoc()) {
                $temperature_int = $row["di_temperature"] . " °C";
                $humidite_int = $row["di_humidite"] . " %";
                echo "<tr>";
                if ($is_admin) {
                    echo "<td>" . $row["di_pk_id"] . "</td>";
                    echo "<td><a href='#' onclick='supprimerDonnee(\"dht22_int\", " . $row["di_pk_id"] . ")'>Supprimer</a></td>";
                }
                echo "<td>" . $row["di_date_heure"] . "</td><td>" . $temperature_int . "</td><td>" . $humidite_int . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "Aucune donnée trouvée pour le capteur dht22_int.";
        }


        // Récupération des 5 dernières données du tableau hx711 avec les identifiants primaires
        $resultat_hx711 = $connexion->query("SELECT h_pk_id, h_date_heure, h_poids FROM hx711 ORDER BY h_date_heure DESC LIMIT 5");
        if ($resultat_hx711->num_rows > 0) {
            echo "<h2>Données du capteur HX711 :</h2>";
            echo "<table border='1'>";
            echo "<tr>";
            if ($is_admin) {
                echo "<th>ID</th>";
                echo "<th>Supprimer</th>";
            }
            echo "<th>Date/Heure</th><th>Poids</th></tr>";
            while ($row = $resultat_hx711->fetch_assoc()) {
                $poids_hx711 = $row["h_poids"] . " g";
                echo "<tr>";
                if ($is_admin) {
                    echo "<td>" . $row["h_pk_id"] . "</td>";
                    echo "<td><a href='#' onclick='supprimerDonnee(\"hx711\", " . $row["h_pk_id"] . ")'>Supprimer</a></td>";
                }
                echo "<td>" . $row["h_date_heure"] . "</td><td>" . $poids_hx711 . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "Aucune donnée trouvée pour le capteur HX711.";
        }

        // Récupération des 5 dernières données du tableau images_stand avec les identifiants primaires
        $resultat_images_stand = $connexion->query("SELECT is_pk_id, is_date_heure, is_img FROM images_stand ORDER BY is_date_heure DESC LIMIT 5");
        if ($resultat_images_stand->num_rows > 0) {
            echo "<h2>Images standard :</h2>";
            echo "<table border='1'>";
            echo "<tr>";
            if ($is_admin) {
                echo "<th>ID</th>";
                echo "<th>Supprimer</th>";
            }
            echo "<th>Date/Heure</th><th>Image</th></tr>";
            while ($row = $resultat_images_stand->fetch_assoc()) {
                $image_data = base64_encode($row['is_img']);
                $image_url = "data:image/jpeg;base64," . $image_data;
                echo "<tr>";
                if ($is_admin) {
                    echo "<td>" . $row["is_pk_id"] . "</td>";
                    echo "<td><a href='#' onclick='supprimerDonnee(\"images_stand\", " . $row["is_pk_id"] . ")'>Supprimer</a></td>";
                }
                echo "<td>" . $row["is_date_heure"] . "</td><td><img src='" . $image_url . "' alt='Image' width='200'></td></tr>";
            }
            echo "</table>";
        } else {
            echo "Aucune image standard trouvée.";
        }

        // Récupération des 5 dernières données du tableau images_infra avec les identifiants primaires
        $resultat_images_infra = $connexion->query("SELECT ii_pk_id, ii_date_heure, ii_img FROM images_infra ORDER BY ii_date_heure DESC LIMIT 5");
        if ($resultat_images_infra->num_rows > 0) {
            echo "<h2>Images infrarouges :</h2>";
            echo "<table border='1'>";
            echo "<tr>";
            if ($is_admin) {
                echo "<th>ID</th>";
                echo "<th>Supprimer</th>";
            }
            echo "<th>Date/Heure</th><th>Image</th></tr>";
            while ($row = $resultat_images_infra->fetch_assoc()) {
                $image_data = base64_encode($row['ii_img']);
                $image_url = "data:image/jpeg;base64," . $image_data;
                echo "<tr>";
                if ($is_admin) {
                    echo "<td>" . $row["ii_pk_id"] . "</td>";
                    echo "<td><a href='#' onclick='supprimerDonnee(\"images_infra\", " . $row["ii_pk_id"] . ")'>Supprimer</a></td>";
                }
                echo "<td>" . $row["ii_date_heure"] . "</td><td><img src='" . $image_url . "' alt='Image' width='200'></td></tr>";
            }
            echo "</table>";
        } else {
            echo "Aucune image infrarouge trouvée.";
        }

        // Fermer la connexion à la base de données
        $connexion->close();
        ?>
    </div>
    <script>
        // Fonction pour envoyer la requête de suppression en utilisant AJAX
        function supprimerDonnee(type, id) {
            if (confirm("Voulez-vous vraiment supprimer cette donnée ?")) {
                fetch(`supprimer.php?type=${type}&id=${id}`)
                    .then(response => response.text())
                    .then(data => {
                        if (data === "success") {
                            // Recharger la page après la suppression réussie
                            location.reload();
                        } else {
                            // Afficher un message d'erreur sans recharger la page
                            alert("Erreur lors de la suppression de la donnée : " + data);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la suppression:', error);
                        // Afficher un message d'erreur sans recharger la page
                        alert("Une erreur s'est produite lors de la suppression de la donnée.");
                    });
            }
        }
    </script>
</body>

</html>