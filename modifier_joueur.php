<?php
session_start();

if(!isset($_SESSION['email'])){
    header('Location: connexion.php');
    exit;
}else{
    require_once('db.php');
    $stmt = $pdo->prepare('SELECT username, role FROM users WHERE email = :email');
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
    <title>E-SPORT GESTION : Modification de statut</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="joueurs.php" class="button">Retour</a>

    <?php
    if($user['role'] !== 'admin'){
        exit("<p id='alert'>Cette page est réservée aux administrateurs du site.</p>");
    }
    ?>

    <h1>Modification de satut</h1>

    <?php
    $id_player = $_GET['id'];
    $stmt = $pdo->prepare('SELECT id, username, email, role, created_at FROM users WHERE id = :id');
    $stmt->execute(array(
        'id' => $id_player
    ));

    $player = $stmt->fetch();
    ?>

    <p id="welcome">Voulez-vous modifier le statut de <?=$player['username']?></p>

    <form method="GET" action="joueurs.php?" id="equipe">
        <input type="hidden" name="id" value="<?= $player['id'] ?>">  <!-- input caché -->

        <label for="statut">Statut : </label>
        <select name="statut" id="statut">
            <option value="player" <?=$player['role'] === 'player' ? 'selected' : '' ?>>Joueur</option>
            <option value="organizer" <?=$player['role'] === 'organizer' ? 'selected' : '' ?>>Organisateur</option>
            <option value="admin" <?=$player['role'] ==='admin' ? 'selected' : '' ?>>Administrateur</option>
        </select>

        <input type="submit" value="Enregistrer">
    </form>

    
</body>
</html>