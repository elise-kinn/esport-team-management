<?php
session_start();

if(isset($_SESSION['user_email'])){
    header('Location: hub.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SPORT GESTION : Connexion</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Connexion</h1>

<form action="#" method="POST" id="form-connexion">
    <div>
            <label for="email">E-mail</label>
        <input type="email" id="email" name="email">
    </div>

    <div>
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password">
    </div>

    <input type="submit" value="Se connecter" name="connexion">
</form>

<div id="options-inscription">
    <p>Pas encore de compte ?</p>
    <a href="inscription.php">Inscrivez-vous ici !</a>
</div>

<?php
if(isset($_POST['connexion'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        echo("<p id='alert'>Tous les champs doivent être remplis :(</p>");
        exit;
    }
    
    require_once('db.php');

    $stmt = $pdo->prepare('SELECT id, password_hash, username FROM users WHERE email = :email');

    $stmt->execute(array(
        'email' => $email
    ));

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user !== false && password_verify($password, $user['password_hash'])){
        //stockage dans la session
        $_SESSION['user_email'] = $email; 
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];

        header("Location: hub.php");
        exit;
    }else{
        echo("<p id='alert'>Mot de passe et/ou indentifiant incorrect :(</p>");
    }
}
?>


</body>
</html>