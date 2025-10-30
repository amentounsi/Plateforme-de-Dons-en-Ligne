<?php
session_start();
require_once 'db.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $montant_total = $_POST['montant_total'];
    $date_limite = $_POST['date_limite'];
    $id_association = $_SESSION['user_id'];

    $today = date('Y-m-d'); // Date système

    if ($date_limite < $today) {
        // Date limite invalide
        echo "<script>alert('❌ La date limite ne peut pas être antérieure à aujourd\'hui.'); window.history.back();</script>";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO projet (id_association, titre, description, montant_total, date_limite) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_association, $titre, $description, $montant_total, $date_limite]);

    header('Location: dashboard_association.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un projet</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <h1>Ajouter un projet</h1>
  <form method="POST">
    <div class="mb-3">
      <label for="titre" class="form-label">Titre</label>
      <input type="text" class="form-control" id="titre" name="titre" required>
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>
    <div class="mb-3">
      <label for="montant_total" class="form-label">Montant requis (TND)</label>
      <input type="number" class="form-control" id="montant_total" name="montant_total" required>
    </div>
    <div class="mb-3">
      <label for="date_limite" class="form-label">Date limite</label>
      <input type="date" class="form-control" id="date_limite" name="date_limite" required>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
    <a href="dashboard_association.php" class="btn btn-secondary">Annuler</a>
  </form>
</div>
</body>
</html>
