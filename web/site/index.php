<?php
// Démarre une session PHP pour gérer les variables de session
session_start();

// Inclure le fichier de configuration contenant les informations sensibles
require_once('../secrets/config.php');

// Connexion à la base de données en utilisant les informations de configuration
$connexion = new mysqli(SERVEUR, UTILISATEUR, MOT_DE_PASSE, BASE_DE_DONNEES);

// Vérifier la connexion à la base de données
if ($connexion->connect_error) {
    // Affiche un message d'erreur en cas d'échec de la connexion
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Variable pour stocker si l'utilisateur est un administrateur ou non
$is_admin = false;

// Vérifie si l'utilisateur est connecté. Sinon, redirige vers la page de connexion
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Vérifie si l'utilisateur est un administrateur
if (isset($_SESSION["username"]) && $_SESSION["username"] === "admin") {
    // Si l'utilisateur est admin, met la variable $is_admin à true
    $is_admin = true;
}

// Définit le fuseau horaire par défaut à "Europe/Paris"
date_default_timezone_set('Europe/Paris');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="icon" type="image/x-icon" href="../img/nichoir.ico">
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <title>Page principale - Nichoir</title>
</head>

<body>
    <!-- Header avec système d'acquisition des données -->
    <div class="header">
        <h1>Nichoir autonome connecté</h1>
        <p>Ce site internet sert de recueil de données captées par le nichoir. Sur cette page, vous trouverez toutes les informations relatives aux capteurs affichées en temps réel.</p>
        <hr>
        <h3>Bienvenue, <?php echo htmlspecialchars($_SESSION["username"]); ?></h3> <!-- Affichage du nom d'utilisateur -->

        <?php
        // Vérification si l'utilisateur est un administrateur
        if ($is_admin) {
            // Traitement pour la modification de la période d'acquisition des données
            if (isset($_POST["u_sched"])) {
                $new_u_sched = $_POST["u_sched"];
                $update_query = "UPDATE users SET u_sched = ? WHERE u_username = ?";
                $stmt_update = $connexion->prepare($update_query);
                $stmt_update->bind_param("is", $new_u_sched, $_SESSION["username"]);
                $stmt_update->execute();
                $stmt_update->close();
                header("Location: " . $_SERVER["PHP_SELF"]); // Redirection vers la même page après la soumission du formulaire
                exit; // Assure que le script s'arrête après la redirection
            }

            // Récupération de la période d'acquisition actuelle de l'utilisateur
            $query = "SELECT u_sched FROM users WHERE u_username = ?";
            $stmt = $connexion->prepare($query);
            $stmt->bind_param("s", $_SESSION["username"]);
            $stmt->execute();
            $stmt->bind_result($current_u_sched);
            $stmt->fetch();
            $stmt->close();

            // Affichage du formulaire pour changer la période d'acquisition
            echo '<hr><form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" class="centered-form">
            <h3>Changer la période d\'acquisition des données:</h3>
            <p>L\'intervalle est actuellement réglé sur ';
            switch ($current_u_sched) {
                case 1:
                    echo '30 minutes.';
                    break;
                case 2:
                    echo '1 heure.';
                    break;
                case 3:
                    echo '2 heures.';
                    break;
                default:
                    echo 'Inconnu.';
                    break;
            }
            echo '</p>';

            // Sélection de la nouvelle période d'acquisition
            echo '<select id="u_sched" name="u_sched">
                <option value="1" ' . ($current_u_sched == 1 ? 'selected' : '') . '>30 minutes</option>
                <option value="2" ' . ($current_u_sched == 2 ? 'selected' : '') . '>1 heure</option>
                <option value="3" ' . ($current_u_sched == 3 ? 'selected' : '') . '>2 heures</option>
            </select>
            <button type="submit">Changer</button>
        </form>';
        }
        ?>

        <hr>
        <p><a class='lien' href="login.php">Se déconnecter</a></p> <!-- lien de déconnexion -->
    </div>
    <!-- Données de temperature et humidité interieur et exterieur -->
    <div class="container_box">

        <div class="temp_hum">
            <h1>Température et humidité</h1>
            <p>Les données suivantes proviennent des capteurs DHT22 situés à l'extérieur et à l'intérieur du nichoir. Ces capteurs permettent d'acquérir simultanément la température et l'humidité relative.</p>
            <img src="../img/DHT22.jpg" style="width:150px;">
        </div>

        <div class="box">
            <h2>Extérieur</h2>
            <?php
            // Récupération des 5 dernières données du tableau dht22_ext avec les identifiants primaires
            $resultat_dht22_ext = $connexion->query("SELECT de_pk_id, de_date_heure, de_temperature, de_humidite FROM dht22_ext ORDER BY de_date_heure DESC LIMIT 5");
            if ($resultat_dht22_ext->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr>";
                // Affichage des colonnes supplémentaires pour l'administrateur si connecté
                if ($is_admin) {
                    echo "<th>ID</th>";
                    echo "<th>Supprimer</th>";
                }
                // En-têtes du tableau de données
                echo "<th>Date et Heure</th><th>Température</th><th>Humidité</th></tr>";
                while ($row = $resultat_dht22_ext->fetch_assoc()) {
                    $temperature_ext = $row["de_temperature"] . " °C";
                    $humidite_ext = $row["de_humidite"] . " %";
                    echo "<tr>";
                    // Affichage des colonnes supplémentaires pour l'administrateur si connecté
                    if ($is_admin) {
                        echo "<td>" . $row["de_pk_id"] . "</td>";
                        // Lien pour supprimer les données avec appel à une fonction JavaScript
                        echo "<td><a class='lien' href='#' onclick='supprimerDonnee(\"dht22_ext\", " . $row["de_pk_id"] . ")'>Supprimer</a></td>";
                    }
                    // Conversion de la date et de l'heure au format français et affichage des données
                    $date_heure = date("d/m/y à H:i", strtotime($row["de_date_heure"]));
                    echo "<td>" . $date_heure . "</td><td>" . $temperature_ext . "</td><td>" . $humidite_ext . "</td></tr>";
                }
                echo "</table>";

                // Récupération des données de date/heure, température et humidité pour le graphique
                $date_heures_data = [];
                $temperatures_data = [];
                $humidites_data = [];
                $resultat_dht22_ext->data_seek(0); // Réinitialiser le pointeur du résultat pour parcourir à nouveau
                while ($row = $resultat_dht22_ext->fetch_assoc()) {
                    $date_heures_data[] = $row["de_date_heure"];
                    $temperatures_data[] = $row["de_temperature"];
                    $humidites_data[] = $row["de_humidite"];
                }
            } else {
                // Message affiché si aucune donnée n'est trouvée pour le capteur dht22_ext.
                echo "Aucune donnée trouvée pour le capteur dht22_ext.";
            }
            ?>
            <br>
            <!-- Bibliothèque Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <!-- Canvas pour le graphique -->
            <canvas id="myChart" width="400" height="200"></canvas>

            <script>
                // Fonction pour formater la date
                function formatDateTime(dateTime) {
                    var date = new Date(dateTime);
                    var options = {
                        day: 'numeric',
                        month: 'long',
                        hour: 'numeric',
                        minute: 'numeric'
                    };
                    return date.toLocaleString('fr-FR', options);
                }

                // Création du graphique avec Chart.js
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode(array_reverse($date_heures_data)); ?>.map(formatDateTime),
                        datasets: [{
                            label: 'Température (°C)',
                            data: <?php echo json_encode(array_reverse($temperatures_data)); ?>,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            fill: false
                        }, {
                            label: 'Humidité (%)',
                            data: <?php echo json_encode(array_reverse($humidites_data)); ?>,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            fill: false
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            </script>
        </div>

        <div class="box">
            <h2>Intérieur</h2>
            <?php
            // Récupération des 5 dernières données du tableau dht22_int avec les identifiants primaires
            $resultat_dht22_int = $connexion->query("SELECT di_pk_id, di_date_heure, di_temperature, di_humidite FROM dht22_int ORDER BY di_date_heure DESC LIMIT 5");
            if ($resultat_dht22_int->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr>";
                // Affichage des colonnes supplémentaires pour l'administrateur si connecté
                if ($is_admin) {
                    echo "<th>ID</th>";
                    echo "<th>Supprimer</th>";
                }
                // En-têtes du tableau de données
                echo "<th>Date et Heure</th><th>Température</th><th>Humidité</th></tr>";
                while ($row = $resultat_dht22_int->fetch_assoc()) {
                    $temperature_int = $row["di_temperature"] . " °C";
                    $humidite_int = $row["di_humidite"] . " %";
                    echo "<tr>";
                    // Affichage des colonnes supplémentaires pour l'administrateur si connecté
                    if ($is_admin) {
                        echo "<td>" . $row["di_pk_id"] . "</td>";
                        // Lien pour supprimer les données avec appel à une fonction JavaScript
                        echo "<td><a class='lien' href='#' onclick='supprimerDonnee(\"dht22_int\", " . $row["di_pk_id"] . ")'>Supprimer</a></td>";
                    }
                    // Conversion de la date et de l'heure au format français et affichage des données
                    $date_heure = date("d/m/y à H:i", strtotime($row["di_date_heure"]));
                    echo "<td>" . $date_heure . "</td><td>" . $temperature_int . "</td><td>" . $humidite_int . "</td></tr>";
                }
                echo "</table>";

                // Récupération des données de date/heure, température et humidité pour le graphique intérieur
                $date_heures_data_int = [];
                $temperatures_data_int = [];
                $humidites_data_int = [];
                $resultat_dht22_int->data_seek(0); // Réinitialiser le pointeur du résultat pour parcourir à nouveau
                while ($row = $resultat_dht22_int->fetch_assoc()) {
                    $date_heures_data_int[] = $row["di_date_heure"];
                    $temperatures_data_int[] = $row["di_temperature"];
                    $humidites_data_int[] = $row["di_humidite"];
                }
            } else {
                // Message affiché si aucune donnée n'est trouvée pour le capteur dht22_int.
                echo "Aucune donnée trouvée pour le capteur dht22_int.";
            }
            ?>
            <br>
            <!-- Canvas pour le graphique intérieur -->
            <canvas id="myChartInt" width="400" height="200"></canvas>

            <script>
                // Création du graphique pour les données intérieures avec Chart.js
                var ctxInt = document.getElementById('myChartInt').getContext('2d');
                var myChartInt = new Chart(ctxInt, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode(array_reverse($date_heures_data_int)); ?>.map(formatDateTime),
                        datasets: [{
                            label: 'Température (°C)',
                            data: <?php echo json_encode(array_reverse($temperatures_data_int)); ?>,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            fill: false
                        }, {
                            label: 'Humidité (%)',
                            data: <?php echo json_encode(array_reverse($humidites_data_int)); ?>,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            fill: false
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            </script>
        </div>

    </div>
    <!-- Données de poids -->
    <div class="container">
        <h1>Poids</h1>
        <p>Les données suivantes viennent du capteur HX711 qui se trouve à l'intérieur du nichoir. Ce capteur récupère la tension en sortie de la cellule de charge, qui sert à la mesure du poids.</p>
        <img src="../img/HX711.jpg" style="width:150px;">
        <br>
        <?php
        // Récupération des 5 dernières données du tableau hx711 avec les identifiants primaires
        $resultat_hx711 = $connexion->query("SELECT h_pk_id, h_date_heure, h_poids FROM hx711 ORDER BY h_date_heure DESC LIMIT 5");
        if ($resultat_hx711->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr>";
            // Affichage des colonnes supplémentaires pour l'administrateur si connecté
            if ($is_admin) {
                echo "<th>ID</th>";
                echo "<th>Supprimer</th>";
            }
            // En-têtes du tableau de données
            echo "<th>Date et Heure</th><th>Poids</th></tr>";
            while ($row = $resultat_hx711->fetch_assoc()) {
                $poids_hx711 = $row["h_poids"] . " g";
                echo "<tr>";
                // Affichage des colonnes supplémentaires pour l'administrateur si connecté
                if ($is_admin) {
                    echo "<td>" . $row["h_pk_id"] . "</td>";
                    // Lien pour supprimer les données avec appel à une fonction JavaScript
                    echo "<td><a class='lien' href='#' onclick='supprimerDonnee(\"hx711\", " . $row["h_pk_id"] . ")'>Supprimer</a></td>";
                }
                // Conversion de la date et de l'heure au format français et affichage des données
                $date_heure = date("d/m/y à H:i", strtotime($row["h_date_heure"]));
                echo "<td>" . $date_heure . "</td><td>" . $poids_hx711 . "</td></tr>";
            }
            echo "</table>";

            // Récupération des données de date/heure et de poids pour le graphique
            $date_heures_data_hx711 = [];
            $poids_data_hx711 = [];
            $resultat_hx711->data_seek(0); // Réinitialiser le pointeur du résultat pour parcourir à nouveau
            while ($row = $resultat_hx711->fetch_assoc()) {
                $date_heures_data_hx711[] = $row["h_date_heure"];
                $poids_data_hx711[] = $row["h_poids"];
            }
        } else {
            // Message affiché si aucune donnée n'est trouvée pour le capteur HX711.
            echo "Aucune donnée trouvée pour le capteur HX711.";
        }
        ?>
        <br>
        <!-- Ajout d'un canvas pour le graphique -->
        <canvas id="myChartHX711" width="400" height="150"></canvas>

        <script>
            // Création du graphique pour les données de poids avec Chart.js
            var ctxHX711 = document.getElementById('myChartHX711').getContext('2d');
            var myChartHX711 = new Chart(ctxHX711, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode(array_reverse($date_heures_data_hx711)); ?>.map(formatDateTime),
                    datasets: [{
                        label: 'Poids (grammes)',
                        data: <?php echo json_encode(array_reverse($poids_data_hx711)); ?>, // Inverser l'ordre des données pour correspondre à l'ordre chronologique
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        </script>
    </div>
    <!-- Données des images standard -->
    <div class="container">
        <h1>Images standards</h1>
        <p>Les images suivantes viennent de la caméra standard Raspicam v2.1 qui se trouve à l'intérieur du nichoir. Cette caméra permet de capturer des images en utilisant des lignes de commandes.</p>
        <img src="../img/camera_standard.jpg" style="width:150px;">
        <br>
        <?php
        // Récupération des 5 dernières données du tableau images_stand avec les identifiants primaires
        $resultat_images_stand = $connexion->query("SELECT is_pk_id, is_date_heure, is_img FROM images_stand ORDER BY is_date_heure DESC LIMIT 5");
        if ($resultat_images_stand->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr>";
            // Affichage des colonnes supplémentaires pour l'administrateur si connecté
            if ($is_admin) {
                echo "<th>ID</th>";
                echo "<th>Supprimer</th>";
            }
            // En-têtes du tableau de données
            echo "<th>Date et Heure</th><th>Image</th></tr>";
            while ($row = $resultat_images_stand->fetch_assoc()) {
                $image_data = base64_encode($row['is_img']);
                $image_url = "data:image/jpeg;base64," . $image_data;
                echo "<tr>";
                // Affichage des colonnes supplémentaires pour l'administrateur si connecté
                if ($is_admin) {
                    echo "<td>" . $row["is_pk_id"] . "</td>";
                    // Lien pour supprimer les données avec appel à une fonction JavaScript
                    echo "<td><a class='lien' href='#' onclick='supprimerDonnee(\"images_stand\", " . $row["is_pk_id"] . ")'>Supprimer</a></td>";
                }
                // Conversion de la date et de l'heure au format français et affichage des données
                $date_heure = date("d/m/y à H:i", strtotime($row["is_date_heure"]));
                echo "<td>" . $date_heure . "</td><td><img src='" . $image_url . "' alt='Image standard' width='200'></td></tr>";
            }
            echo "</table>";
        } else {
            // Message affiché si aucune image standard n'est trouvée.
            echo "Aucune image standard trouvée.";
        }
        ?>
    </div>
    <!-- Données des images infrarouges -->
    <div class="container">
        <h1>Images infrarouges</h1>
        <p>Les images suivantes viennent de la caméra infrarouge AMG8833 qui se trouve à l'intérieur du nichoir. Cette caméra infrarouge permet de capturer des images à l'aide d'un capteur de résolution 8x8 pixels.</p>
        <img src="../img/camera_infrarouge.jpg" style="width:150px;">
        <br>
        <?php
        // Récupération des 5 dernières données du tableau images_infra avec les identifiants primaires
        $resultat_images_infra = $connexion->query("SELECT ii_pk_id, ii_date_heure, ii_img FROM images_infra ORDER BY ii_date_heure DESC LIMIT 5");
        if ($resultat_images_infra->num_rows > 0) {

            echo "<table border='1'>";
            echo "<tr>";
            // Affichage des colonnes supplémentaires pour l'administrateur si connecté
            if ($is_admin) {
                echo "<th>ID</th>";
                echo "<th>Supprimer</th>";
            }
            // En-têtes du tableau de données
            echo "<th>Date et Heure</th><th>Image</th></tr>";
            while ($row = $resultat_images_infra->fetch_assoc()) {
                $image_data = base64_encode($row['ii_img']);
                $image_url = "data:image/jpeg;base64," . $image_data;
                echo "<tr>";
                // Affichage des colonnes supplémentaires pour l'administrateur si connecté
                if ($is_admin) {
                    echo "<td>" . $row["ii_pk_id"] . "</td>";
                    // Lien pour supprimer les données avec appel à une fonction JavaScript
                    echo "<td><a class='lien' href='#' onclick='supprimerDonnee(\"images_infra\", " . $row["ii_pk_id"] . ")'>Supprimer</a></td>";
                }
                // Conversion de la date et de l'heure au format français et affichage des données
                $date_heure = date("d/m/y à H:i", strtotime($row["ii_date_heure"]));
                echo "<td>" . $date_heure . "</td><td><img src='" . $image_url . "' alt='Image infrarouge' width='200'></td></tr>";
            }
            echo "</table>";
        } else {
            // Message affiché si aucune image infrarouge n'est trouvée.
            echo "Aucune image infrarouge trouvée.";
        }


        // Fermer la connexion à la base de données
        $connexion->close();
        ?>
    </div>
    <!-- Footer Lafayette -->
    <div class="footer">
        <p>© 2024 Lycée Lafayette.</p>
    </div>
    <!-- Bouton pour revenir en haut de la page -->
    <button id="backToTopBtn">↑</button>
    <script src="script.js"></script>
    <!-- Script pour supprimer les données dans les tableaux -->
    <script>
        // Fonction pour envoyer la requête de suppression en utilisant AJAX
        function supprimerDonnee(type, id) {
            // Enregistrer la position de défilement actuelle
            const scrollPosition = window.scrollY;

            if (confirm("Voulez-vous vraiment supprimer cette donnée ?")) {
                fetch(`del.php?type=${type}&id=${id}`)
                    .then(response => response.text())
                    .then(data => {
                        if (data === "success") {
                            // Recharger la page après la suppression réussie
                            window.scrollTo(0, scrollPosition); // Restaurer la position de défilement après le rechargement
                            location.reload();
                        } else {
                            // Afficher un message d'erreur sans recharger la page
                            alert("Erreur lors de la suppression de la donnée : " + data);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la suppression:', error);
                        // Afficher un message d'erreur sans recharger la page
                        alert("Une erreur s'est produite lors de la suppression de la donnée : " + error.message);
                    });
            }
        }
    </script>

</body>

</html>