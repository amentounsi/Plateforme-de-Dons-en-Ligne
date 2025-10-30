<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

// Récupération sécurisée des champs
$pseudo = $_POST['pseudo'] ?? '';
$mot_de_passe = $_POST['password'] ?? '';

try {
    // Vérification côté donateur
    $stmt = $pdo->prepare("SELECT * FROM donateur WHERE pseudo = :pseudo");
    $stmt->execute(['pseudo' => $pseudo]);
    $user = $stmt->fetch();

    if ($user && $user['mot_de_passe'] === $mot_de_passe) {
        $_SESSION['role'] = 'donateur';
        $_SESSION['user_id'] = $user['id'];
        header("Location:dashboard_donateur.php");
        exit;
    }

    // Vérification côté association
    $stmt = $pdo->prepare("SELECT * FROM association WHERE pseudo = :pseudo");
    $stmt->execute(['pseudo' => $pseudo]);
    $user = $stmt->fetch();

    if ($user && $user['mot_de_passe'] === $mot_de_passe) {
        $_SESSION['role'] = 'association';
        $_SESSION['user_id'] = $user['id'];
        header("Location:dashboard_association.php");
        exit;
    }

    // Si échec
    header("Location:login.html?error=1");
    exit;

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
