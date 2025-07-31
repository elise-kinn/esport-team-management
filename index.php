<?php
session_start();

if(isset($_GET['deconnexion'])){
    session_start();
    session_unset();     // Supprime les variables de la session
    session_destroy();   // Détruit la session

    $message = "Vous avez bien été déconnecté·e";
}

if(isset($_SESSION['email'])){
    header('Location: hub.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SPORT GESTION</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
<?php
if(isset($message)){
    echo('<p id="alert">'.$message.'</p>');
}
?>

    <h1>E-SPORT GESTION</h1>

    <div id="div-button">
        <a href="connexion.php" class='button'>Se connecter</a>
        <a href="inscription.php" class='button'>S'inscrire</a>
    </div>
</body>
</html>