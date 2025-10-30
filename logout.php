<?php
session_start();

// Détruire toutes les données de session
$_SESSION = array();
session_destroy();

// Rediriger vers la page de connexion
header("Location: login.html");
exit;
?>