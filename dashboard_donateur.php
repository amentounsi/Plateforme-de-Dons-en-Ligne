<?php
session_start();
require_once 'db.php';



$id_donateur = $_SESSION['user_id'];

// Récupérer tous les projets actifs (date limite >= aujourd'hui)
// Récupérer tous les projets actifs (date limite >= aujourd'hui)
$projets = $pdo->prepare("SELECT * FROM projet WHERE date_limite >= CURDATE()");
$projets->execute();
$projets = $projets->fetchAll();


// Historique des dons de ce donateur
$stmt = $pdo->prepare("SELECT don.*, projet.titre 
                       FROM don 
                       JOIN projet ON projet.id = don.id_projet 
                       WHERE id_donateur = ?");
$stmt->execute([$id_donateur]);
$dons = $stmt->fetchAll();

// Total des dons du donateur
$total_dons = $pdo->prepare("SELECT IFNULL(SUM(montant), 0) FROM don WHERE id_donateur = ?");
$total_dons->execute([$id_donateur]);
$montant_total = $total_dons->fetchColumn();

// Message d'erreur ou de succès
$message = '';
if (isset($_GET['error']) && $_GET['error'] === 'montant') {
    $message = "<div class='alert alert-danger'>Le montant donné dépasse le montant requis pour ce projet.</div>";
} elseif (isset($_GET['success'])) {
    $message = "<div class='alert alert-success'>Merci pour votre don !</div>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Espace Donateur - HelpHub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
  <h1 class="mb-4">Bienvenue Donateur ❤️</h1>
  <p>Total de vos dons : <strong><?= number_format($montant_total, 2) ?> TND</strong></p>
  <div class="d-flex gap-3 mb-3">
    <a href="modifier_profil_donateur.php" class="btn btn-warning btn-sm">🖊️ Modifier Profil</a>
    <a href="logout.php" class="btn btn-light btn-sm">Déconnexion</a>
  </div>
  <?= $message ?>

  <h3 class="mt-5">Projets disponibles</h3>
  <table class="table table-hover">
    <thead class="table-primary">
      <tr>
        <th>Titre</th>
        <th>Montant requis</th>
        <th>Collecté</th>
        <th>Date limite</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($projets as $projet): ?>
        <tr>
          <td><?= htmlspecialchars($projet['titre']) ?></td>
          <td><?= $projet['montant_total'] ?> TND</td>
          <td><?= $projet['montant_collecte'] ?> TND</td>
          <td><?= $projet['date_limite'] ?></td>
          <td>
            <?php if ($projet['montant_collecte'] >= $projet['montant_total']): ?>
              <span class="badge bg-success">Projet financé</span>
            <?php else: ?>
              <form action="donner.php" method="POST" class="d-flex">
                <input type="hidden" name="id_projet" value="<?= $projet['id'] ?>">
                <input type="number" name="montant" class="form-control me-2" placeholder="Montant" required min="1" step="1">
                <button type="submit" class="btn btn-success btn-sm">Donner</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h3 class="mt-5">Vos dons</h3>
  <?php if (count($dons) === 0): ?>
    <p>Vous n'avez pas encore effectué de dons.</p>
  <?php else: ?>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Projet</th>
          <th>Montant</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($dons as $don): ?>
          <tr>
            <td><?= htmlspecialchars($don['titre']) ?></td>
            <td><?= $don['montant'] ?> TND</td>
            <td><?= $don['date_don'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
