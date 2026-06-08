<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../connexion.php"); exit; }
require '../db.php';

$id   = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM expeditions WHERE id=?");
$stmt->execute([$id]);
$e = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$e) { header("Location: liste.php"); exit; }

$clients = $pdo->query("SELECT * FROM clients ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->prepare("UPDATE expeditions SET client_id=?,ville_depart=?,ville_arrivee=?,poids=?,frais_transport=?,date_depart=?,statut=? WHERE id=?")
        ->execute([intval($_POST['client_id']),trim($_POST['ville_depart']),trim($_POST['ville_arrivee']),floatval($_POST['poids']),floatval($_POST['frais_transport']),$_POST['date_depart'],$_POST['statut'],$id]);
    header("Location: liste.php?success=1"); exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Expédition — Atlas Transport</title>
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
            <h2>✏️ Modifier l'Expédition</h2>
            <a href="liste.php" class="btn btn-secondary">← Retour</a>
        </div>
        <div class="form-card">
            <p style="font-size:13px;color:var(--gray-text);margin-bottom:20px">
                Référence : <strong><?= htmlspecialchars($e['reference']) ?></strong>
            </p>
            <div class="form-section-title">Modifier les informations</div>
            <form method="POST">
                <div class="form-group">
                    <label>Client *</label>
                    <select name="client_id" required>
                        <?php foreach ($clients as $cl): ?>
                        <option value="<?= $cl['id'] ?>" <?= $cl['id']==$e['client_id']?'selected':'' ?>>
                            <?= htmlspecialchars($cl['prenom'].' '.$cl['nom']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Ville de départ *</label><input type="text" name="ville_depart" required value="<?= htmlspecialchars($e['ville_depart']) ?>"></div>
                    <div class="form-group"><label>Ville d'arrivée *</label><input type="text" name="ville_arrivee" required value="<?= htmlspecialchars($e['ville_arrivee']) ?>"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Poids (kg)</label><input type="number" name="poids" step="0.01" value="<?= $e['poids'] ?>"></div>
                    <div class="form-group"><label>Frais (MAD)</label><input type="number" name="frais_transport" step="0.01" value="<?= $e['frais_transport'] ?>"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Date de départ</label><input type="date" name="date_depart" value="<?= $e['date_depart'] ?>"></div>
                    <div class="form-group">
                        <label>Statut *</label>
                        <select name="statut" required>
                            <?php foreach(['En attente','En cours de route','Livrée'] as $i=>$s): ?>
                            <option value="<?= $s ?>" <?= $s===$e['statut']?'selected':'' ?>><?= ['⏳','🚛','✅'][$i].' '.$s ?></option>
                            <?php endforeach; ?>
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
