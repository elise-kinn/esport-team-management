<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SPORT GESTION : Inscription</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Inscription</h1>

<form action="#" method="POST" id="form-inscription">
    <div>
        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username">
    </div>

    <div>
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email">
    </div>

    <div>
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password">
    </div>

    <div>
        <label for="password2">Confirmez votre mot de passe</label>
        <input type="password" id="password2" name="password2">
    </div>

    <input type="submit" value="S'inscrire" name="inscription">
</form>

<div id="options-inscription">
    <p>Déjà inscrit·e ? </p>
    <a href="index.php">Connectez-vous ici !</a>
</div>

<?php
if(isset($_POST['inscription'])){
    $user_name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if(empty($email) || empty($password) || empty($password2) || empty($user_name)){
        echo("<p id='alert'>Tous les champs doivent être remplis :(</p>");
        exit;
    }

    if($password !== $password2){
        echo("<p id='alert'>La confirmation de votre mot de passe a échoué :(</p>");
        exit;  
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo("<p id='alert'>Votre adresse e-mail est incorrect :(</p>");
        exit;
    }

    if(strlen($user_name) < 4){
        echo("<p id='alert'>Votre nom d'utilisateur doit faire plus de 4 caractères :(</p>");
        exit;
    }
    
    if(strlen($password) < 8){
        echo("<p id='alert'>Votre mot de passe doit faire plus de 8 caractères :(</p>");
        exit;
    }

    require_once('db.php');

    // Vérification si l'email est unique
    $stmt_verif_email = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt_verif_email->execute(array(
        'email' => $email,
    ));

    $verif_email = $stmt_verif_email->fetchColumn();

    // Vérification si le mot de passe est unique
    $stmt_verif_username = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt_verif_username->execute(array(
        'username' => $user_name,
    ));

    $verif_username = $stmt_verif_username->fetchColumn();

    if($verif_email){
        echo("<p id='alert'>Votre adresse e-mail est déjà liée à un compte existant :(</p>");
        exit;
    }else if($verif_username){
        echo("<p id='alert'>Votre nom d'utilisateur est déjà utilisé :(</p>");
        exit;
    }else{
        // Insertion
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt_insert = $pdo->prepare('INSERT INTO users(username, email, password_hash) VALUES (:username, :email, :password_hash)');
    
        $stmt_insert->execute(array(
            'username' => $user_name,
            'email' => $email,
            'password_hash' => $password_hash,
        ));

        header("Location: hub.php");
        exit;
    }
}
?>

</body>
</html>