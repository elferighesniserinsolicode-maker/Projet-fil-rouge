<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../connexion.php"); exit; }
require '../db.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$c = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$c) { header("Location: liste.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("UPDATE clients SET nom=?,prenom=?,telephone=?,email=?,adresse=? WHERE id=?")
        ->execute([trim($_POST['nom']),trim($_POST['prenom']),trim($_POST['telephone']),trim($_POST['email']),trim($_POST['adresse']),$id]);
    header("Location: liste.php?success=1"); exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Client — Atlas Transport</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<nav class="navbar">
    <a href="../dashboard.php" class="brand">🚚 Atlas Transport Maroc</a>
    <div class="nav-links"><a href="../logout.php" class="btn-nav">Déconnexion</a></div>
</nav>
<div class="dash-layout">
    <aside class="sidebar">
        <div class="sidebar-logo">🚚 Atlas Transport</div>
        <div class="sidebar-section">Principal</div>
        <a href="../dashboard.php">📊 &nbsp;Dashboard</a>
        <div class="sidebar-section">Gestion</div>
        <a href="liste.php" class="active">👥 &nbsp;Clients</a>
        <a href="../expeditions/liste.php">📦 &nbsp;Expéditions</a>
        <div class="sidebar-section">Compte</div>
        <a href="../logout.php">🚪 &nbsp;Déconnexion</a>
    </aside>
    <main class="main-content">
        <div class="page-hdr">
            <h2>✏️ Modifier le Client</h2>
            <a href="liste.php" class="btn btn-secondary">← Retour</a>
        </div>
        <div class="form-card">
            <div class="form-section-title">Modifier les informations</div>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group"><label>Nom *</label><input type="text" name="nom" required value="<?= htmlspecialchars($c['nom']) ?>"></div>
                    <div class="form-group"><label>Prénom *</label><input type="text" name="prenom" required value="<?= htmlspecialchars($c['prenom']) ?>"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Téléphone</label><input type="tel" name="telephone" value="<?= htmlspecialchars($c['telephone']) ?>"></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= htmlspecialchars($c['email']) ?>"></div>
                </div>
                <div class="form-group"><label>Adresse</label><input type="text" name="adresse" value="<?= htmlspecialchars($c['adresse']) ?>"></div>
                <div style="display:flex;gap:12px;margin-top:10px">
                    <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                    <a href="liste.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </main>
</div>
<script src="../script.js"></script>
</body>
</html>
