<?php
header('Content-Type: application/json');
require_once 'db.php';

try {
    $stats = [];
    $stats['total_projets'] = $pdo->query("SELECT COUNT(*) FROM projet")->fetchColumn();
    $stats['total_donateurs'] = $pdo->query("SELECT COUNT(*) FROM donateur")->fetchColumn();
    $stats['total_dons'] = $pdo->query("SELECT IFNULL(SUM(montant), 0) FROM don")->fetchColumn();
    $stats['total_associations'] = $pdo->query("SELECT COUNT(*) FROM association")->fetchColumn();

    echo json_encode($stats);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>