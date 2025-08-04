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
    <title>E-SPORT GESTION : Équipes</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="hub.php" class="button">Retour</a>
    <h1>Équipes</h1>

    <?php
    if($user['role'] === 'player'):
    ?>

    <a href="creer_equipe.php" class="button create">Créer une nouvelle équipe</a>

    <div id="filter">
        <form method="POST" action="equipes.php">
            <label for='filtre'>Filtre : </label>
            <select name="filtre" id="filtre">
                <option value="0">Toutes les équipes</option>                
                <option value="1">Mes équipes</option>                
                <option value="2">Mes équipes CAPITAINE</option>
            </select>

            <input type="submit" value="Filtrer" name="filtrer">
        </form>
    </div>
    <?php
    endif
    ?>

    <div>
        <?php
        if($user['role'] === 'admin'){
            $delete = isset($_GET['delete']) ? $_GET['delete'] : '';

            if($delete){
                $id_delete = $_GET['id'];

                $stmt_delete = $pdo->prepare('DELETE FROM teams WHERE id = :id');
                $stmt_delete->execute(array(
                    'id' => $id_delete
                ));

                echo"<p id='alert'>La suppression de l'équipe a été effectuée avec succès</p> !";
            }

        }


        if($user['role'] === 'player'){

        $join = isset($_GET['join']) ? $_GET['join'] : '';

        if($join == "yes"){ //Rejoindre une équipe
            $stmt_join = $pdo->prepare('INSERT INTO team_members(user_id, team_id) VALUES (:user, :team)');
            $stmt_join->execute(array(
                'user' => $user['id'],
                'team' => $_GET['id']
            ));

            if($stmt_join->rowCount() > 1){
                echo"<p id='alert'>Une erreur s'est produite :( Veuillez réessayer.</p>";
            }else{
                echo"<p id='alert'>Vous avez rejoint votre équipe avec succès !</p>";
            }
        }

            $filtre = isset($_POST['filtre']) ? $_POST['filtre'] : -1;
    
            switch($filtre){
                case 0:
                    $titre = "Toutes les équipes";
                    break;
                case 1:
                    $titre = "Toutes vos équipes en tant que membre";
                    break;
                case 2:
                    $titre = "Toutes vos équipes en tant que capitaine";
                    break;
                default:
                    $titre = "Toutes les équipes";
                    break;
            }
    
            echo "<h2>$titre</h2>";
        }

        $stmt_list = $pdo->prepare('
            SELECT 
                t.id,
                t.name, 
                t.created_at,
                tm.role_in_team
            FROM teams AS t
            LEFT JOIN team_members AS tm ON tm.team_id = t.id AND tm.user_id = :id
        ');

        $stmt_list->execute(['id' => $user['id']]);

        $teams = $stmt_list->fetchAll();

        foreach ($teams as $team) {
            if($user['role'] === 'player'){
                $role = $team['role_in_team']; // mon rôle
    
                if (!isset($_POST['filtre'])) {
                    // Toutes les équipes
                } else if ($_POST['filtre'] == 1 && !$role) {
                    continue; // Fitre les équipes dont je ne suis pas membre
                } else if ($_POST['filtre'] == 2 && $role !== 'captain') {
                    continue; // Fitre les équipes dont je ne suis pas capitaine
                }
            }

            // Affichage de l'article
            echo "<article class='list-team'>";

                echo'<div class="div-list">';
                    echo "<h3>{$team['name']}</h3>";
                    echo "<p>Date de création : {$team['created_at']}</p>";
                echo "</div>";

                echo'<div class="div-list">';

                if($user['role'] === 'player'){

                    if ($role === 'captain') {
                        echo "<a href='gestion_equipe.php?id={$team['id']}'>Gérer l'équipe</a>";
                    } else if (!$role) {
                        echo "<a href='rejoindre_equipe.php?id={$team['id']}'>Rejoindre l'équipe</a>";
                    }
                }else if($user['role'] === 'admin'){
                    echo "<a href='supprimer_equipe.php?id={$team['id']}'>Supprimer l'équipe</a>";
                }


                echo'</div>';

            echo "</article>";
        }
        ?>
    </div>

</body>
</html>