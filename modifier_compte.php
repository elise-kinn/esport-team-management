<?php
session_start();

if(!isset($_SESSION['email'])){
    header('Location: connexion.php');
    exit;
}else{
    require_once('db.php');
    $stmt = $pdo->prepare('SELECT id, username, email, role FROM users WHERE email = :email');
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
    <title>E-SPORT GESTION : Paramètres</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
        <a href="hub.php" class="button">Retour</a>

    <h1>Paramètres</h1>
    <h2>Mettre à jour vos informations</h2>
    
    <form action="#" method="POST" id="form-modifier">
        <div>
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" value='<?=$user['username']?>'>
            <input type="submit" value="Mettre à jour" name="modifier-username">
        </div>

        <?php
        if(isset($_POST['modifier-username'])){
            $user_name = $_POST['username'];

            $stmt_username = $pdo->prepare('SELECT * FROM users WHERE username = :username');
            $stmt_username->execute(array(
                'username' => $user_name
            ));

            $verif_username = $stmt_username->fetchColumn();

            if($verif_username){
                echo("<p id='alert'>Votre nom d'utilisateur est déjà utilisé :(</p>");
            }else if(strlen($user_name) < 4){
                echo("<p id='alert'>Votre nom d'utilisateur doit faire plus de 4 caractères :(</p>");
            }else{
                $stmt_insert_username = $pdo->prepare('UPDATE users SET username = :username WHERE id = :id');
        
                $stmt_insert_username->execute(array(
                    'username' => $user_name,
                    'id' => $user['id']
                ));

                echo("<p id='alert'>Votre nom d'utilisateur a bien été modifié !</p>");
            }
        }
        ?>

        <div>
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value='<?=$user['email']?>'>
            <input type="submit" value="Mettre à jour" name="modifier-email">
        </div>

        <?php
        if(isset($_POST['modifier-email'])){
            $email = $_POST['email'];

            $stmt_email = $pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt_email->execute(array(
                'email' => $email
            ));

            $verif_email = $stmt_email->fetchColumn();

            if($verif_email){
                echo("<p id='alert'>Votre adresse mail est déjà utilisé par un autre compte :(</p>");
            }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo("<p id='alert'>Votre adresse e-mail est incorrect :(</p>");
            }else{
                $stmt_insert_email = $pdo->prepare('UPDATE users SET email = :email WHERE id = :id');
        
                $stmt_insert_email->execute(array(
                    'email' => $email,
                    'id' => $user['id']
                ));

                echo("<p id='alert'>Votre adresse mail a bien été modifiée</p>");
            }
        }
        ?>

    </form>
</body>


</html>