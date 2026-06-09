<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../connexion.php"); exit; }
require '../db.php';

$clients = $pdo->query("SELECT * FROM clients ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref = 'EXP-'.date('Y').'-'.str_pad(rand(1,9999),4,'0',STR_PAD_LEFT);
    $pdo->prepare("INSERT INTO expeditions (reference,client_id,ville_depart,ville_arrivee,poids,frais_transport,date_depart,statut) VALUES (?,?,?,?,?,?,?,?)")
        ->execute([$ref,intval($_POST['client_id']),trim($_POST['ville_depart']),trim($_POST['ville_arrivee']),floatval($_POST['poids']),floatval($_POST['frais_transport']),$_POST['date_depart'],$_POST['statut']]);
    header("Location: liste.php?success=1"); exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle Expédition — Atlas Transport</title>
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
        <a href="../clients/liste.php">👥 &nbsp;Clients</a>
        <a href="liste.php" class="active">📦 &nbsp;Expéditions</a>
        <div class="sidebar-section">Compte</div>
        <a href="../logout.php">🚪 &nbsp;Déconnexion</a>
    </aside>
    <main class="main-content">
        <div class="page-hdr">
            <h2>➕ Nouvelle Expédition</h2>
            <a href="liste.php" class="btn btn-secondary">← Retour</a>
        </div>
        <div class="form-card">
            <div class="form-section-title">Détails de l'expédition</div>
            <form method="POST">
                <div class="form-group">
                    <label>Client *</label>
                    <select name="client_id" required>
                        <option value="">-- Choisir un client --</option>
                        <?php foreach ($clients as $cl): ?>
                        <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['prenom'].' '.$cl['nom']) ?> — <?= htmlspecialchars($cl['telephone']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Ville de départ *</label><input type="text" name="ville_depart" required placeholder="Ex: Casablanca"></div>
                    <div class="form-group"><label>Ville d'arrivée *</label><input type="text" name="ville_arrivee" required placeholder="Ex: Marrakech"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Poids (kg) *</label><input type="number" name="poids" step="0.01" min="0" required placeholder="Ex: 42.50"></div>
                    <div class="form-group"><label>Frais de transport (MAD) *</label><input type="number" name="frais_transport" step="0.01" min="0" required placeholder="Ex: 350.00"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Date de départ *</label><input type="date" name="date_depart" required></div>
                    <div class="form-group">
                        <label>Statut *</label>
                        <select name="statut" required>
                            <option value="En attente">⏳ En attente</option>
                            <option value="En cours de route">🚛 En cours de route</option>
                            <option value="Livrée">✅ Livrée</option>
                        </select>
                    </div>
                </div>
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
