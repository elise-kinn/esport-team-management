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
        $update = isset($_GET['statut']) ? $_GET['statut'] : "";

        if($update){
            $id_update = $_GET['id'];

            $stmt_update = $pdo->prepare('UPDATE users SET role = :statut WHERE id = :id');
            $stmt_update->execute(array(
                'id' => $id_update,
                'statut' => $update
            ));

            echo"<p id='alert'>La modification de statut a été effectuée avec succès</p> !";
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
            <p><?=$joueur['email']?></p>
            <p><?=$joueur['role']?></p>
            <a href="modifier_joueur.php?id=<?=$joueur['id']?>" style="color : #5f1f1f">Modifier</a>
        </article>

        <?php
        endforeach
        ?>
    </div>
    
</body>
</html>