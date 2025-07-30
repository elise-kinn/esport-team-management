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
    <title>E-SPORT GESTION : Déconnexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Déconnexion</h1>
    <p id="p-logout">Êtes-vous sûr·e de vouloir vous déconnecter, <?=$_SESSION['user_name']?>?</p>

    <div id="div-button">
        <a href="index.php?deconnexion=yes" class="button red">OUI, Se déconnecter</a>
        <a href="hub.php" class="button">NON, Rester connecté·e</a>
    </div>
    
</body>
</html>