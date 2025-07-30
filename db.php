<?php
$host = 'localhost';
$dbname = 'esports';
$username = 'dodo';
$password = '83210';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $th) {
    die("Erreur de connexion à la base : " . $th->getMessage());
}
?>