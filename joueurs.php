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
    <title>E-SPORT GESTION : Joueurs</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="hub.php" class="button">Retour</a>
    <?php
    if($user['role'] !== 'admin'){
        exit("<p id='alert'>Cette page est réservée aux administrateurs du site.</p>");
    }
    ?>

    <h1>Liste des joueurs</h1>

    <?php
        $delete = isset($_GET['delete']) ? $_GET['delete'] : "";

        if($delete){
            $id_delete = $_GET['id'];

            $stmt_delete = $pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt_delete->execute(array(
                'id' => $id_delete
            ));

            echo"<p id='alert'>La suppression du compte a été effectuée avec succès</p> !";
        }
    ?>

    <div id="list-player">
        <?php
        $stmt = $pdo->prepare('SELECT id, username, email, role FROM users');
        $stmt->execute();

        $joueurs = $stmt->fetchAll();

        foreach ($joueurs as $joueur) :
        ?>

        <article>
            <h2><?=$joueur['username']?></h2>
            <p><?=$joueur['role']?></p>
            <p><?=$joueur['email']?></p>
            <a href="supprimer_joueur.php?id=<?=$joueur['id']?>">Supprimer</a>
        </article>

        <?php
        endforeach
        ?>
    </div>
    
</body>
</html>