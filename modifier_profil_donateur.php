<?php
session_start();
require_once 'db.php';



$id_donateur = $_SESSION['user_id'];
$message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'] ?? '';
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'] ?? '';

    // Récupérer les données actuelles
    $stmt = $pdo->prepare("SELECT mot_de_passe FROM donateur WHERE id = ?");
    $stmt->execute([$id_donateur]);
    $user = $stmt->fetch();

    if (!$user) {
        $message = "<div class='alert alert-danger'>Utilisateur introuvable.</div>";
    } elseif ($ancien_mot_de_passe !== $user['mot_de_passe']) {
        $message = "<div class='alert alert-danger'>Ancien mot de passe incorrect.</div>";
    } else {
        // Mise à jour des informations
        $stmt = $pdo->prepare("UPDATE donateur SET email = ?, mot_de_passe = ? WHERE id = ?");
        $stmt->execute([$email, $nouveau_mot_de_passe, $id_donateur]);
        $message = "<div class='alert alert-success'>Informations mises à jour avec succès !</div>";
    }
}

// Récupérer les informations actuelles pour pré-remplir le formulaire
$stmt = $pdo->prepare("SELECT email FROM donateur WHERE id = ?");
$stmt->execute([$id_donateur]);
$donateur = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier Profil Donateur - HelpHub</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <h1>Modifier Mes Informations</h1>
  <?= $message ?>
  <form method="POST">
    <div class="mb-3">
      <label for="email" class="form-label">Nouvel Email</label>
      <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($donateur['email']) ?>" required>
    </div>
    <div class="mb-3">
      <label for="ancien_mot_de_passe" class="form-label">Ancien Mot de Passe</label>
      <input type="password" class="form-control" id="ancien_mot_de_passe" name="ancien_mot_de_passe" required>
    </div>
    <div class="mb-3">
      <label for="nouveau_mot_de_passe" class="form-label">Nouveau Mot de Passe</label>
      <input type="password" class="form-control" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="dashboard_donateur.php" class="btn btn-secondary">Annuler</a>
  </form>
</div>
</body>
</html>
