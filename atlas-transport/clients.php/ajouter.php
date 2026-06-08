<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: ../connexion.php");
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("INSERT INTO clients (nom, prenom, telephone, email, adresse) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['nom'], $_POST['prenom'],
        $_POST['telephone'], $_POST['email'], $_POST['adresse']
    ]);
    header("Location: liste.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Ajouter Client</title><link rel="stylesheet" href="../style.css"></head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <div class="sidebar-logo">🚚 Atlas Transport</div>
        <a href="liste.php" class="sidebar-item active">👥 Clients</a>
        <a href="../expeditions/liste.php" class="sidebar-item">📦 Expéditions</a>
        <a href="../logout.php" class="sidebar-item">🚪 Déconnexion</a>
    </aside>
    <main class="main">
        <div class="page-header">
            <h1>Ajouter un Client</h1>
            <a href="liste.php" class="btn-back">← Retour</a>
        </div>
        <div class="form-card">
            <form method="POST">
                <div class="form-group"><label>Nom</label><input type="text" name="nom" required></div>
                <div class="form-group"><label>Prénom</label><input type="text" name="prenom" required></div>
                <div class="form-group"><label>Téléphone</label><input type="text" name="telephone"></div>
                <div class="form-group"><label>Email</label><input type="email" name="email"></div>
                <div class="form-group"><label>Adresse</label><textarea name="adresse" rows="3"></textarea></div>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </form>
        </div>
    </main>
</div>
</body>
</html>