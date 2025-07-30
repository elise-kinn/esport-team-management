<?php
session_start();

if(!isset($_SESSION['user_email'])){
    header('Location: connexion.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SPORT GESTION : Hub</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <a href="deconnexion.php" class="button">Déconnexion</a>
    <h1>HUB</h1>
    <p id="welcome">Bienvenue, <?=$_SESSION['user_name']?></p>
</body>
</html>