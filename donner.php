<?php
session_start();
require_once 'db.php';



// Vérifier les données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_donateur = $_SESSION['user_id'];
    $id_projet = $_POST['id_projet'] ?? null;
    $montant = $_POST['montant'] ?? null;

    // Sécurité : s'assurer que le projet existe et que le montant est correct
    if (!$id_projet || !$montant || $montant <= 0) {
        header('Location:dashboard_donateur.php?error=montant');
        exit;
    }

    // Récupérer les informations du projet
    $stmt = $pdo->prepare("SELECT montant_total, montant_collecte FROM projet WHERE id = ?");
    $stmt->execute([$id_projet]);
    $projet = $stmt->fetch();

    if (!$projet) {
        header('Location:dashboard_donateur.php?error=projet');
        exit;
    }

    $montant_restant = $projet['montant_total'] - $projet['montant_collecte'];

    // Vérifier si le montant donné dépasse le montant restant
    if ($montant > $montant_restant) {
        header('Location:dashboard_donateur.php?error=montant_depasse');
        exit;
    }

    // Insérer le don dans la base
    $stmt = $pdo->prepare("INSERT INTO don (id_donateur, id_projet, date_don, montant) VALUES (?, ?, NOW(), ?)");
    $stmt->execute([$id_donateur, $id_projet, $montant]);

    // Mettre à jour le montant_collecte du projet
    $stmt2 = $pdo->prepare("UPDATE projet SET montant_collecte = montant_collecte + ?, montant_total = montant_total - ? WHERE id = ?");
    $stmt2->execute([$montant, $montant, $id_projet]);

    // Rediriger vers le tableau de bord
    header('Location: dashboard_donateur.php?success=1');
    exit;
} else {
    // Accès direct non autorisé
    header('Location: dashboard_donateur.php');
    exit;
}
?>
