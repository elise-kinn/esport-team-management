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
    <title>E-SPORT GESTION : Tournois</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="hub.php" class="button">Retour</a>
    <h1>Tournois</h1>

    <div id="filter">
        <form method="POST">
            <label for='filtre'>Filtre : </label>
            <select name="filtre" id="filtre">
                <option value="0">Tous les tournois</option>                
                <option value="1">Mes tournois</option>                
            </select>

            <input type="submit" value="Filtrer" name="filtrer">
        </form>
    </div>

    <div>
        <?php

        $filtre = isset($_POST['filtre']) ? $_POST['filtre'] : -1;

        switch($filtre){
            case 0:
                $titre = "Tous les tournois";
                break;
            case 1:
                $titre = "Les tounois auxquels je suis inscrit·e";
                break;
            default:
                $titre = "Tous les tournois";
                break;
        }

        echo "<h2>$titre</h2>";
        
        if (!isset($_POST['filtre']) || $_POST['filtre'] == 0) {
            // Tous les tournois
            $stmt_list = $pdo->prepare('
                SELECT 
                    tr.id AS tournoi_id,
                    tr.name AS tournoi_nom,
                    tr.game,
                    tr.description,
                    tr.start_date,
                    tr.end_date
                FROM tournaments AS tr
            ');

            $stmt_list->execute();

        } else if ($_POST['filtre'] == 1) {

            // Tournois auxquels mes équipes sont inscrites

            $stmt_list = $pdo->prepare('
                SELECT DISTINCT 
                    tr.id AS tournoi_id,
                    tr.name AS tournoi_nom,
                    tr.game,
                    tr.description,
                    tr.start_date,
                    tr.end_date
                FROM tournaments AS tr
                JOIN registrations AS re ON re.tournament_id = tr.id
                JOIN team_members AS tm ON tm.team_id = re.team_id
                WHERE tm.user_id = :id_user
            ');

            $stmt_list->execute(['id_user' => $user['id']]);
        }

        $tournois = $stmt_list->fetchAll();

        foreach ($tournois as $tournoi) {
            if ($tournoi['start_date'] < date('Y-m-d')) {
                continue; // Ignore les tournois passés
            }

            echo "<article class='list-team'>";
                echo "<div>";
                    echo "<h3>{$tournoi['tournoi_nom']}</h3>";
                    echo "<p>{$tournoi['game']}</p>";
                echo"</div>";
                echo"<div>";
                    echo "<p>{$tournoi['description']}</p>";
                    echo "<p>Date : {$tournoi['start_date']}</p>";
                echo"</div>";
            echo"<a href='rejoindre_tournoi.php?id={$tournoi['tournoi_id']}'>Rejoindre le tournoi</a>";
            echo "</article>";
        }
        ?>
    </div>

</body>
</html>