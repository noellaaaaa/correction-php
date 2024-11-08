<?php

$servername = "mysql:host=localhost;dbname=e-commerce;charset=utf-8";
$uesrname = "root";
$password = "";


// connexion
try {
    $pdo = new PDO("mysql:host=localhost;dbname=e-commerce","root","");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connexion réussie !"; // Message de succès si la connexion est établie

} catch (PDOException $e) {
    die('Erreur de connexion à la base de données: ' . $e->getMessage()); // Affiche l'erreur de connexion

}
?>
