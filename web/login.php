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

// Vérification des informations d'identification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Vérifier que les champs ne sont pas vides
    if (!empty($username) && !empty($password)) {
        // Utilisation des requêtes préparées pour éviter les injections SQL
        $query = "SELECT * FROM users WHERE u_username = ? AND u_password = ?";
        $stmt = $connexion->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Identifiants valides, démarrer une session et rediriger vers la page principale
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            header("location: index.php");
            exit();
        } else {
            // Identifiants invalides, afficher un message d'erreur
            $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        // Les champs sont vides, afficher un message d'erreur
        $error_message = "Veuillez saisir un nom d'utilisateur et un mot de passe.";
    }
}

// Fermer la connexion à la base de données
$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="nichoir.ico">
    <title>Identification - Nichoir</title>
    <link rel="stylesheet" type="text/css" href="bdd.css">
</head>

<body>

    <div class="container">
        <h1>Système de surveillance pour nid d'oiseau</h1>
        <h2>Identification</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div>
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>
            </div>
            <br>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <br>
            <button type="submit">Se connecter</button>
        </form>
        <?php if (isset($error_message)) { ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php } ?>
    </div>
</body>

</html>