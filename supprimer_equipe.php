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

$id_equipe = $_GET['id']
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SPORT GESTION : Suppression d'équipe</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="equipes.php" class="button">Retour</a>
    <?php
    if($user['role'] !== 'admin'){
        exit("<p id='alert'>Cette page est réservée aux administrateurs du site.</p>");
    }
    ?>
    <h1>Suppression d'équipe</h1>
    
    <?php
    $stmt = $pdo->prepare('SELECT id, name, created_at FROM teams WHERE id = :id');
    $stmt->execute(array(
        'id' => $id_equipe
    ));
    
    $equipe = $stmt->fetch()
    ?>
    <p id="welcome">Êtes-vous sûr·e de vouloir supprimer <?=$equipe['name']?> ?</p>

    <div id="equipe">
        <h2><?=$equipe['name']?></h2>
        <p>Date de création : <?=$equipe['created_at']?></p>
    </div>

    <div id="div-button">
        <a href="equipes.php?delete=yes&id=<?=$equipe['id']?>" class="button red">Supprimer</a>
        <a href="equipes.php" class="button">Annuler</a>
    </div>
</body>
</html>