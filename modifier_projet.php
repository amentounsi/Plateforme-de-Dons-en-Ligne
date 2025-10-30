<?php
// modifier_projet.php
session_start();
require_once 'db.php';



$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: dashboard_association.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $montant_total = $_POST['montant_total'];
    $date_limite = $_POST['date_limite'];

    $stmt = $pdo->prepare("UPDATE projet SET titre=?, description=?, montant_total=?, date_limite=? WHERE id=? AND id_association=?");
    $stmt->execute([$titre, $description, $montant_total, $date_limite, $id, $_SESSION['user_id']]);

    header('Location: dashboard_association.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM projet WHERE id=? AND id_association=?");
$stmt->execute([$id, $_SESSION['user_id']]);
$projet = $stmt->fetch();

if (!$projet) {
    echo "Projet introuvable.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier le projet</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <h1>Modifier le projet</h1>
  <form method="POST">
    <div class="mb-3">
      <label for="titre" class="form-label">Titre</label>
      <input type="text" class="form-control" id="titre" name="titre" value="<?= htmlspecialchars($projet['titre']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($projet['description']) ?></textarea>
    </div>
    <div class="mb-3">
      <label for="montant_total" class="form-label">Montant requis (TND)</label>
      <input type="number" class="form-control" id="montant_total" name="montant_total" value="<?= $projet['montant_total'] ?>" required>
    </div>
    <div class="mb-3">
      <label for="date_limite" class="form-label">Date limite</label>
      <input type="date" class="form-control" id="date_limite" name="date_limite" value="<?= $projet['date_limite'] ?>" required>
    </div>
    <button type="submit" class="btn btn-warning">Enregistrer</button>
    <a href="dashboard_association.php" class="btn btn-secondary">Annuler</a>
  </form>
</div>
</body>
</html>