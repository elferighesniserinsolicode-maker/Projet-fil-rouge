<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: ../connexion.php");
require '../config.php';

$id = $_GET['id'] ?? 0;
$client = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$client->execute([$id]);
$c = $client->fetch(PDO::FETCH_ASSOC);
if (!$c) { header("Location: liste.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE clients SET nom=?, prenom=?, telephone=?, email=?, adresse=? WHERE id=?");
    $stmt->execute([$_POST['nom'], $_POST['prenom'], $_POST['telephone'], $_POST['email'], $_POST['adresse'], $id]);
    header("Location: liste.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Modifier Client</title><link rel="stylesheet" href="../style.css"></head>
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
            <h1>Modifier le Client</h1>
            <a href="liste.php" class="btn-back">← Retour</a>
        </div>
        <div class="form-card">
            <form method="POST">
                <div class="form-group"><label>Nom</label><input type="text" name="nom" value="<?= htmlspecialchars($c['nom']) ?>" required></div>
                <div class="form-group"><label>Prénom</label><input type="text" name="prenom" value="<?= htmlspecialchars($c['prenom']) ?>" required></div>
                <div class="form-group"><label>Téléphone</label><input type="text" name="telephone" value="<?= htmlspecialchars($c['telephone']) ?>"></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($c['email']) ?>"></div>
                <div class="form-group"><label>Adresse</label><textarea name="adresse" rows="3"><?= htmlspecialchars($c['adresse']) ?></textarea></div>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </form>
        </div>
    </main>
</div>
</body>
</html>
