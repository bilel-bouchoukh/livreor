<?php

require_once "config.php";

var_dump($db);

if (isset($_POST["login_inscription"]) && isset($_POST["password_inscription"])) {
    $login = $_POST["login_inscription"];
    $password = $_POST["password_inscription"];

    if ($user->createUser($login, $password)) {
        echo "Utilisateur enregistré en base de données.";
        // header("location: http://localhost/files_laplateforme/livredor/inscription_connexion");
        exit;
    } else {
        echo "Erreur lors de l'enregistrement utilisateur.";
    }
}

if (isset($_POST["login_connexion"]) && isset($_POST["password_connexion"])) {
    $login = $_POST["login_connexion"];
    $password = $_POST["password_connexion"];

    if ($user->connectUser($login, $password)) {
        echo "Utilisateur connecté.";
        // header("Location: http://localhost/files_laplateforme/livredor/inscription_connexion");
        exit;
    } else {
        echo "Erreur de connection.";
    }
   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription/connexion</title>
</head>
<body>

    <div class="container">
        <div class="item_inscription">
            <form action="" method="post">

            <label for="login">
                <input type="text" name="login_inscription" placeholder="login Inscription">
            </label>

            <label for="password">
                <input type="text" name="password_inscription" placeholder="mot de passe utilisateur">
            </label>

            <button type="submit">Inscription</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="item_connexion">
            <form action="" method="post">

            <label for="login">
                <input type="text" name="login_connexion" placeholder="login Connexion">
            </label>

            <label for="password">
                <input type="text" name="password_connexion" placeholder="mot de passe utilisateur">
            </label>

            <button type="submit">Connexion</button>
            </form>
        </div>
    </div>
    
    
</body>
</html>