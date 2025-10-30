<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'association') {
    header('Location: login.html');
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Vérifier si le projet a des dons associés
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM don WHERE id_projet = ?");
    $stmt->execute([$id]);
    $nb_dons = $stmt->fetchColumn();

    if ($nb_dons == 0) {
        // Aucun don trouvé, suppression autorisée
        $stmt = $pdo->prepare("DELETE FROM projet WHERE id = ? AND id_association = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        
        header('Location: dashboard_association.php?delete=success');
        exit;
    } else {
        // Il y a des dons, suppression interdite
        header('Location: dashboard_association.php?error=projet_has_dons');
        exit;
    }
} else {
    header('Location: dashboard_association.php?error=invalid_project');
    exit;
}
?>
