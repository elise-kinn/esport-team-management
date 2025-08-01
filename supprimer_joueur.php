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

$id_joueur = $_GET['id']
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SPORT GESTION : Suppression de compte</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="joueurs.php" class="button">Retour</a>
    <h1>Suppression de compte</h1>
    
    <?php
    $stmt = $pdo->prepare('SELECT id, username, email, role FROM users WHERE id = :id');
    $stmt->execute(array(
        'id' => $id_joueur
    ));
    
    $joueur = $stmt->fetch()
    ?>
    <p id="welcome">Êtes-vous sûr·e de vouloir supprimer <?=$joueur['username']?> ?</p>

    <div id="joueur">
        <h2><?=$joueur['username']?></h2>
        <p><?=$joueur['email']?></p>
        <p><?=$joueur['role']?></p>
    </div>

    <div id="div-button">
        <a href="joueurs.php?delete=yes&id=<?=$joueur['id']?>" class="button red">Supprimer</a>
        <a href="joueurs.php" class="button">Annuler</a>
    </div>
</body>
</html>