<?php
session_start();

if(!isset($_SESSION['email'])){
    header('Location: connexion.php');
    exit;
}else{
    require_once('db.php');
    $stmt = $pdo->prepare('SELECT username, role FROM users WHERE email = :email');
    $stmt->execute(array(
        'email' => $_SESSION['email']
    ));

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <div id="div-button">
        <a href="deconnexion.php" class="button">Déconnexion</a>
        <a href="modifier_compte.php" class="button">Modifier mes informations</a>
    </div>
        
    <h1>HUB</h1>
    <p id="welcome">Bienvenue, <?=$user['username']?> (<?=$user['role']?>)</p>

    <div id="div-button">
        <a href="equipes.php" class="button-hub">ÉQUIPES</a>
        <a href="tournois.php"  class="button-hub">TOURNOIS</a>
    </div>
</body>
</html>