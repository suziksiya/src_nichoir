<?php
session_start();

// Inclure le fichier de configuration
require_once('../secrets/config.php');

// Connexion à la base de données
$connexion = new mysqli(SERVEUR, UTILISATEUR, MOT_DE_PASSE, BASE_DE_DONNEES);

// Vérifier la connexion
if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Vérification des informations d'identification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assurer que les données sont bien définies et non vides
    if (isset($_POST["username"], $_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
        // Utiliser des requêtes préparées pour éviter les injections SQL
        $query = "SELECT * FROM users WHERE u_username = ? AND u_password = ?";
        $stmt = $connexion->prepare($query);

        // Lier les paramètres
        $stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
        $stmt->execute();
        $result = $stmt->get_result();

        // Vérifier le résultat de la requête
        if ($result->num_rows == 1) {
            // Identifiants valides, démarrer une session et rediriger vers la page principale
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $_POST["username"];
            header("location: index.php");
            exit();
        } else {
            // Identifiants invalides, définir un message d'erreur dans la session
            $_SESSION["error_message"] = "Nom d'utilisateur ou mot de passe incorrect.";
            // Rediriger vers la même page
            header("location: {$_SERVER['PHP_SELF']}");
            exit();
        }
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
    <link rel="icon" type="image/x-icon" href="../img/nichoir.ico">
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <title>Identification - Nichoir</title>
</head>

<body>
    <div class="container">
        <h1>Nichoir autonome connecté</h1>
        <h2>Identification:</h2>
        <form class="centered-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div>
                <label for="username"></label>
                <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required>
            </div>

            <div>
                <label for="password"></label>
                <input type="password" id="password" name="password" placeholder="Mot de passe" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <?php if (!empty($_SESSION["error_message"])) { ?>
            <p style="color: #9e363a;"><?php echo $_SESSION["error_message"]; ?></p>
            <?php unset($_SESSION["error_message"]); ?>
        <?php } ?>
    </div>
</body>

</html>