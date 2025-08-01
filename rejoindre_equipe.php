<?php
session_start();

if(!isset($_SESSION['email'])){
    header('Location: connexion.php');
    exit;
}else{
    require_once('db.php');
    $stmt = $pdo->prepare('SELECT id, username, email FROM users WHERE email = :email');
    $stmt->execute(array(
        'email' => $_SESSION['email']
    ));

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

$id_team = $_GET['id'];

$stmt_team = $pdo->prepare('SELECT teams.name, teams.created_at, COUNT(*) AS count FROM teams LEFT JOIN team_members AS tm ON tm.team_id = teams.id WHERE team_id = :id GROUP BY team_id');
$stmt_team->execute(array(
    'id' => $id_team
));

$team = $stmt_team->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SPORT GESTION : Rejoindre</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <a href="equipes.php" class="button">Retour</a>
    <h1>Rejoindre <?=$team['name']?> ?</h1>
    <p id="welcome">Êtes-vous sûr·e de vouloir rejoindre cette équipe ?</p>

    <div id='team'>
        <h2><?=$team['name']?></h2>
        <p>Date de création : <?=$team['created_at']?></p>
        <p>Nombre de membre : <?=$team['count']?></p>

        <div>
            <?php
            $stmt_equipe = $pdo->prepare('SELECT role_in_team, username FROM team_members AS tm LEFT JOIN users ON user_id = users.id WHERE tm.team_id = :id');
            $stmt_equipe->execute(array(
                'id' => $id_team
            ));

            $membres = $stmt_equipe->fetchAll(PDO::FETCH_ASSOC);

            foreach($membres as $membre) :
            ?>
                <div>
                    <h3><?=$membre['username']?></h3>
                    <p><?=$membre['role_in_team'] === "captain" ? "Capitaine" : "Membre" ?></p>
                </div>
            <?php
            endforeach
            ?>
        </div>
    </div>
    <a href="equipes.php?id=<?=$id_team?>&join=yes" class="button" style="margin : 15px auto">Rejoindre</a>

</body>
</html>