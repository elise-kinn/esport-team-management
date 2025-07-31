<?php
session_start();

if(!isset($_SESSION['email'])){
    header('Location: connexion.php');
    exit;
}else{
    require_once('db.php');
    $stmt = $pdo->prepare('SELECT username, role, id FROM users WHERE email = :email');
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
    <title>E-SPORT GESTION : Inscription</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="hub.php" class="button">Retour</a>
    <h1>Équipes</h1>

    <div id="filter">
        <form method="POST">
            <label for='filtre'>Filtre : </label>
            <select name="filtre" id="filtre">
                <option value="0">Toutes les équipes</option>                
                <option value="1">MEs équipes</option>                
                <option value="2">Mes équipes CAPITAINE</option>
            </select>

            <input type="submit" value="Filtrer" name="filtrer">
        </form>
    </div>
    <div>
        <?php
        require_once('db.php');
        // var_dump($_POST['filtre']);

        if($_POST['filtre'] == 0){ // TOUTES 
            $stmt_list = $pdo->prepare('SELECT name, created_at FROM teams');

            $stmt_list->execute();

            echo('<p class="titre-list">Toutes les équipes</p>');

        }else if($_POST['filtre'] == 1){ // MES EQUIPES
            $stmt_list = $pdo->prepare('
                SELECT name, created_at 
                FROM teams AS t
                LEFT JOIN team_members AS tm ON tm.team_id = t.id
                WHERE tm.user_id = :id
            ');

            $stmt_list->execute(array(
                'id' => $user['id']
            ));

            echo('<p class="titre-list">Toutes les équipes dont je suis membre</p>');

        }else if($_POST['filtre'] == 2){ // MES EQUIPES CAPITAINE
            $stmt_list = $pdo->prepare('
                SELECT name, created_at 
                FROM teams AS t
                LEFT JOIN team_members AS tm ON tm.team_id = t.id
                WHERE tm.user_id = :id AND role_in_team = :cap
            ');

            $stmt_list->execute(array(
                'id' => $user['id'],
                'cap' => 'capitaine'
            ));

            echo('<p class="titre-list">Toutes les équipes dont je suis capitaine</p>');

        }

        $teams = $stmt_list->fetchAll();

        foreach ($teams as $team) :
        ?>

        <article class="list-team">
            <h2><?=$team['name']?></h2>
            <p>Date de création : <?=$team['created_at']?></p>
            <div>


            </div>
        </article>

        <?php
        endforeach
        ?>
    </div>

</body>
</html>