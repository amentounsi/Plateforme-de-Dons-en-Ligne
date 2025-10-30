<?php
require_once 'db.php';

$role = $_POST['role'];
$nom = $_POST['nom'];
$email = $_POST['email'];
$cin = $_POST['cin'];
$pseudo = $_POST['pseudo'] ?? '';
$password = $_POST['password'];

try {
    // Vérifier si le pseudo existe déjà
    $stmtCheck = $pdo->prepare("
        SELECT COUNT(*) FROM (
            SELECT pseudo FROM donateur WHERE pseudo = :pseudo
            UNION ALL
            SELECT pseudo FROM association WHERE pseudo = :pseudo
        ) AS combined
    ");
    $stmtCheck->execute(['pseudo' => $pseudo]);
    $count = $stmtCheck->fetchColumn();

    if ($count > 0) {
        // Afficher une alerte sans redirection
        echo "<script>alert('⚠️ Ce pseudo est déjà utilisé. Veuillez choisir un autre.'); window.history.back();</script>";
        exit;
    }

    // Sinon, on continue
    if ($role === 'donateur') {
        $stmt = $pdo->prepare("INSERT INTO donateur (nom, prenom, email, cin, pseudo, mot_de_passe)
            VALUES (:nom, '', :email, :cin, :pseudo, :password)");
        $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'cin' => $cin,
            'pseudo' => $pseudo,
            'password' => $password
        ]);
    } elseif ($role === 'association') {
        $nom_association = $_POST['nom_association'];
        $identifiant_fiscal = $_POST['identifiant_fiscal'];
        $adresse_association = $_POST['adresse_association'] ?? '';
        $logo = null;
        $logo_type = null;

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $logo = file_get_contents($_FILES['logo']['tmp_name']);
            $logo_type = mime_content_type($_FILES['logo']['tmp_name']);
        }

        $stmt = $pdo->prepare("
            INSERT INTO association (
                nom, prenom, email, cin, nom_association, adresse_association, identifiant_fiscal, logo, logo_type, pseudo, mot_de_passe
            ) VALUES (
                :nom, '', :email, :cin, :nom_association, :adresse_association, :identifiant_fiscal, :logo, :logo_type, :pseudo, :password
            )
        ");

        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cin', $cin);
        $stmt->bindParam(':nom_association', $nom_association);
        $stmt->bindParam(':adresse_association', $adresse_association);
        $stmt->bindParam(':identifiant_fiscal', $identifiant_fiscal);
        $stmt->bindParam(':logo', $logo, PDO::PARAM_LOB);
        $stmt->bindParam(':logo_type', $logo_type);
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
    }

    header("Location: login.html?register=success");
    exit;

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
