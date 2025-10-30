<?php
session_start();
require_once 'db.php';

// Vérifie que l'utilisateur est connecté et qu'il est une association


$id_association = $_SESSION['user_id'];

// Récupère l’ID du projet depuis l’URL
$id_projet = $_GET['id'] ?? null;

if (!$id_projet) {
    echo "ID de projet manquant.";
    exit;
}

// Vérifie si le projet appartient à cette association
$stmt = $pdo->prepare("SELECT * FROM projet WHERE id = ? AND id_association = ?");
$stmt->execute([$id_projet, $id_association]);
$projet = $stmt->fetch();

if (!$projet) {
    echo "Projet non trouvé ou vous n'avez pas accès à ce projet.";
    exit;
}

// Récupère les dons liés à ce projet
$stmt = $pdo->prepare("SELECT don.*, donateur.nom, donateur.prenom 
                       FROM don 
                       JOIN donateur ON don.id_donateur = donateur.id 
                       WHERE don.id_projet = ?");
$stmt->execute([$id_projet]);
$dons = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dons du Projet - HelpHub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
  <h2>Dons pour le projet : <?= htmlspecialchars($projet['titre']) ?></h2>
  <a href="dashboard_association.php" class="btn btn-secondary btn-sm mb-3">← Retour au tableau de bord</a>

  <?php if (count($dons) === 0): ?>
    <div class="alert alert-info">Aucun don n’a été effectué pour ce projet pour le moment.</div>
  <?php else: ?>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Donateur</th>
          <th>Montant</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($dons as $don): ?>
          <tr>
            <td><?= htmlspecialchars($don['prenom'] . ' ' . $don['nom']) ?></td>
            <td><?= number_format($don['montant'], 2) ?> TND</td>
            <td><?= $don['date_don'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
