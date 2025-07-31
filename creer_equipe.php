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
    <a href="equipes.php" class="button">Retour</a>
    <h1>Création de ton équipe</h1>

    <form action="" method="POST" id="form-team">
        <label for="nom">Nom d'équipe</label>
        <input type="text" id='nom' name="nom">

        <input type="submit" name="create">
    </form>

    <?php
    if(isset($_POST['create'])){
        $nom = $_POST['nom'];

        if(empty($nom)){
            exit("<p id='alert'>Veuillez donner un nom à votre équipe</p>");
        }

        if(strlen($nom) < 3 || strlen($nom) > 25){
            exit("<p id='alert'>Votre nom d'équipe doit faire entre 3 et 25 caractères</p>");
        }

        // Vérification si le nom est unique

        $stmt_verif = $pdo->prepare('SELECT * FROM teams WHERE name = :nom');
        $stmt_verif->execute(array(
            'nom' => $nom
        ));

        $verif = $stmt_verif->fetchColumn();

        if($verif){
            exit("<p id='alert'>Ce nom d'équipe existe déja, veuillez en choisir un autre :(</p>");
        }

        $stmt_insert = $pdo->prepare('INSERT INTO teams(name) VALUES (:nom)');
        $stmt_insert->execute(array(
            'nom' => $nom
        ));

        $last_id = $pdo->lastInsertId(); // récupération de l'id de la team créée

        // INSERT

        $stmt_insert_captain = $pdo->prepare('INSERT INTO team_members(user_id, team_id, role_in_team) VALUES (:user_id, :team_id, :cap)');
        $stmt_insert_captain->execute(array(
            'user_id' => $user['id'],
            'team_id' => $last_id,
            'cap' => 'captain'
        ));

        // Vérification de l'insert

        if($stmt_insert->rowCount() > 1){
            echo"<p id='alert'>Une erreur s'est produite :( Veuillez réessayer.</p>";
        }else{
            echo"<p id='alert'>Votre équipe a été créée avec succès !</p>";
        }

    }
    ?>
    
</body>
</html>