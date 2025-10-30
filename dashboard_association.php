<?php
session_start();
require_once 'db.php';

// Sécurité : vérifier que l'association est connectée
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

$id_asso = $_SESSION['user_id'];

// 🔥 Charger les données de l'association
$stmt = $pdo->prepare("SELECT * FROM association WHERE id = ?");
$stmt->execute([$id_asso]);
$association = $stmt->fetch();

// 🔥 Charger les projets de cette association
$stmtProjets = $pdo->prepare("SELECT * FROM projet WHERE id_association = ?");
$stmtProjets->execute([$id_asso]);
$projets = $stmtProjets->fetchAll();

// 🔥 Calculer le total collecté
$stmtTotal = $pdo->prepare("SELECT SUM(montant) FROM don WHERE id_projet IN (SELECT id FROM projet WHERE id_association = ?)");
$stmtTotal->execute([$id_asso]);
$total_collecte = $stmtTotal->fetchColumn() ?? 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Espace Association - HelpHub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
   .logo-asso{
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 15px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
  </style>
</head>
<body>

<div class="header text-center bg-primary text-white py-4">
  <h1>Bienvenue Responsable Association 🏢</h1>
  <p><strong><?= htmlspecialchars($association['nom_association'] ?? 'Nom inconnu') ?></strong></p>

  <!-- Affichage du logo -->
  <?php if (!empty($association['logo'])): ?>
  <img src="data:<?= htmlspecialchars($association['logo_type']) ?>;base64,<?= base64_encode($association['logo']) ?>" alt="Logo" class="logo-asso">
<?php endif; ?>



  <p>Total collecté : <strong><?= number_format($total_collecte, 2) ?> TND</strong></p>

  <div class="d-flex justify-content-center gap-3">
    <a href="modifier_profil_association.php" class="btn btn-warning btn-sm">🖊️ Modifier Profil</a>
    <a href="logout.php" class="btn btn-light btn-sm">Déconnexion</a>
  </div>
</div>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Vos projets</h3>
    <a href="ajouter_projet.php" class="btn btn-success">➕ Ajouter un projet</a>
  </div>

  <?php if (empty($projets)): ?>
    <div class="alert alert-info">Aucun projet trouvé. Cliquez sur "Ajouter un projet" pour commencer.</div>
  <?php else: ?>
    <table class="table table-striped table-bordered">
      <thead class="table-primary">
        <tr>
          <th>Titre</th>
          <th>Montant demandé</th>
          <th>Collecté</th>
          <th>Date limite</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projets as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['titre']) ?></td>
            <td><?= number_format($p['montant_total'], 2) ?> TND</td>
            <td><?= number_format($p['montant_collecte'], 2) ?> TND</td>
            <td><?= htmlspecialchars($p['date_limite']) ?></td>
            <td class="d-flex gap-2">
              <a href="modifier_projet.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
              <a href="supprimer_projet.php?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce projet ?')">🗑 Supprimer</a>
              <a href="voir_dons_projet.php?id=<?= $p['id'] ?>" class="btn btn-info btn-sm">📊 Dons</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

</body>
<?php if (isset($_GET['delete']) && $_GET['delete'] === 'success'): ?>
  <div class="alert alert-success text-center">
    ✅ Projet supprimé avec succès.
  </div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'projet_has_dons'): ?>
  <div class="alert alert-danger text-center">
    ❌ Impossible de supprimer : ce projet a déjà reçu des dons.
  </div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'invalid_project'): ?>
  <div class="alert alert-warning text-center">
    ⚠️ Projet invalide ou introuvable.
  </div>
<?php endif; ?>

</html>
