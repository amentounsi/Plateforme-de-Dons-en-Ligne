<?php
session_start();
require_once 'db.php';

// Sécurité : accessible uniquement au responsable association
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'association') {
    header('Location: login.html');
    exit;
}

$id_association = $_SESSION['user_id'];
$message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'] ?? '';
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'] ?? '';

    // Récupérer les données actuelles
    $stmt = $pdo->prepare("SELECT mot_de_passe FROM association WHERE id = ?");
    $stmt->execute([$id_association]);
    $user = $stmt->fetch();

    if (!$user) {
        $message = "<div class='alert alert-danger'>Utilisateur introuvable.</div>";
    } elseif ($ancien_mot_de_passe !== $user['mot_de_passe']) {
        $message = "<div class='alert alert-danger'>Ancien mot de passe incorrect.</div>";
    } else {
        // Préparer la mise à jour de l'email et du mot de passe
        $query = "UPDATE association SET email = ?, mot_de_passe = ?";
        $params = [$email, $nouveau_mot_de_passe];

        // Vérifier si un nouveau logo est uploadé
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $logo = file_get_contents($_FILES['logo']['tmp_name']);
            $logo_type = mime_content_type($_FILES['logo']['tmp_name']);
            $query .= ", logo = ?, logo_type = ?";
            $params[] = $logo;
            $params[] = $logo_type;
        }

        $query .= " WHERE id = ?";
        $params[] = $id_association;

        $stmtUpdate = $pdo->prepare($query);
        $stmtUpdate->execute($params);

        $message = "<div class='alert alert-success'>Profil mis à jour avec succès !</div>";
    }
}

// Récupérer les infos actuelles pour affichage
$stmt = $pdo->prepare("SELECT email, logo, logo_type FROM association WHERE id = ?");
$stmt->execute([$id_association]);
$association = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier Profil - HelpHub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    .logo-preview {
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
<div class="container mt-5">
  <h1>Modifier Mon Profil</h1>
  <?= $message ?>

  <!-- Afficher le logo actuel -->
  <?php if (!empty($association['logo'])): ?>
    <img src="data:<?= htmlspecialchars($association['logo_type']) ?>;base64,<?= base64_encode($association['logo']) ?>" alt="Logo actuel" class="logo-preview">
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="email" class="form-label">Nouvel Email</label>
      <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($association['email']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="ancien_mot_de_passe" class="form-label">Ancien Mot de Passe</label>
      <input type="password" class="form-control" id="ancien_mot_de_passe" name="ancien_mot_de_passe" required>
    </div>

    <div class="mb-3">
      <label for="nouveau_mot_de_passe" class="form-label">Nouveau Mot de Passe</label>
      <input type="password" class="form-control" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required>
    </div>

    <div class="mb-3">
      <label for="logo" class="form-label">Changer le Logo (optionnel)</label>
      <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
    </div>

    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="dashboard_association.php" class="btn btn-secondary">Annuler</a>
  </form>
</div>
</body>
</html>
