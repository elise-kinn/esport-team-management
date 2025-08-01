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

require_once('db.php');
$tournament_id = $_GET['id'];

$stmt = $pdo->prepare('SELECT name, game, description, start_date, end_date, username FROM tournaments LEFT JOIN users ON users.id = organizer_id WHERE tournaments.id = :id');

$stmt->execute(array(
    'id' => $tournament_id
));
$tournament = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SPORT GESTION : S'inscrire à un tournoi</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="tournois.php" class="button">Retour</a>
    <h1>Inscription à</h1>
    <p id="welcome">Choisissez l'équipe qui va participer à l'évènement : </p>

    <div id="team-details">
        <h2><?=$tournament['name']?> on <?=$tournament['game']?></h2>
        <p><?=$tournament['description']?> Organisé par : <?=$tournament['username']?></p>
        <p></p>
        <div>
            <p>Date de début : <?=$tournament['start_date']?></p>
            <p>Date de fin : <?=$tournament['end_date']?></p>
        </div>
    </div>

    <form action="#" method="POST" id="filter">
        <label for="team-select">Équipe :</label>
        <select name="team" id="team-select">
            <?php
                $stmt_teams = $pdo->prepare('SELECT teams.id, teams.name FROM teams LEFT JOIN team_members ON team_id = teams.id WHERE user_id = :user_id');
                $stmt_teams->execute(array(
                    'user_id' => $user['id']
                ));

                $teams = $stmt_teams->fetchAll();

                foreach ($teams as $team):
            ?>
                <option value="<?=$team['id']?>"><?=$team['name']?></option>
            <?php
            endforeach
            ?>
        </select>
        <input type="submit" name="join_tournament" value="Rejoindre le tournoi">
    </form>

    <?php
    if(isset($_POST['join_tournament'])){
        $joining_team_id = $_POST['team'];

        $stmt_insert = $pdo->prepare('INSERT INTO registrations(team_id, tournament_id) VALUES (:team_id, :tournament_id)');
        $stmt_insert->execute(array(
            'team_id' => $joining_team_id,
            'tournament_id' => $tournament_id
        ));

        if($stmt_insert->rowCount() > 1){
            echo "<p id='alert'>Une erreur s'est produite :( Veuillez réessayer.</p>";
        }else{
            echo "<p id='alert'>Votre équipe est inscrite !</p>";
        }

    }
    ?>
    
</body>
</html>