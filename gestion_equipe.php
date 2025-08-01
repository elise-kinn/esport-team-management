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
    <title>E-SPORT GESTION : Gestion d'équipe</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="equipes.php" class="button">Retour</a>
    <h1>Gestion des rôles</h1>
    <p id="welcome">Modifiez les rôles de votre équipe : </p>
    <h2 style="margin-top : 15px"><?=$team['name']?></h2>
    
    <form method="POST" id="gestion-role-form">
        <?php
        $stmt_equipe = $pdo->prepare('SELECT role_in_team, username FROM team_members AS tm LEFT JOIN users ON user_id = users.id WHERE tm.team_id = :id');
        $stmt_equipe->execute(array(
            'id' => $id_team
        ));

        $membres = $stmt_equipe->fetchAll(PDO::FETCH_ASSOC);

        foreach($membres as $membre) :
        ?>

        <div>
            <label for="role"><?=$membre['username']?></label>
            <select name="role" id="role">
                <option value="0" <?=$membre['role_in_team'] === 'captain' ? "selected" : ''?>>Capitaine</option>
                <option value="1" <?=$membre['role_in_team'] === 'member' ? "selected" : ''?>>Membre</option>
            </select>

        </div>

        <?php
        endforeach
        ?>

        <input type="submit" name="role" value="Enregistrer les modifications">
    </form>

    <?php
    if(isset($_POST['role'])){
        
    }

    ?>

</body>
</html>