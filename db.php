<?php
$host = 'localhost';
$dbname = 'bd_projet';  // Vérifie que c’est bien le nom de ta base
$user = 'root';       // Par défaut sous XAMPP/WAMP
$pass = '';           // Mot de passe vide par défaut sous XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>