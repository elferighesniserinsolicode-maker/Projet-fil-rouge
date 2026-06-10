<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../connexion.php"); exit; }
require '../db.php';

$search = trim($_GET['q'] ?? '');
if ($search) {
    $stmt = $pdo->prepare("SELECT e.*,c.nom,c.prenom FROM expeditions e JOIN clients c ON e.client_id=c.id WHERE e.reference LIKE ? OR e.ville_depart LIKE ? OR e.ville_arrivee LIKE ? OR c.nom LIKE ? ORDER BY e.id DESC");
    $like = "%$search%";
    $stmt->execute([$like,$like,$like,$like]);
} else {
    $stmt = $pdo->query("SELECT e.*,c.nom,c.prenom FROM expeditions e JOIN clients c ON e.client_id=c.id ORDER BY e.id DESC");
}
$expeditions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expéditions — Atlas Transport</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<nav class="navbar">
    <a href="../dashboard.php" class="brand">🚚 Atlas Transport Maroc</a>
    <div class="nav-links">
        <span style="color:rgba(255,255,255,0.65);font-size:13px">👤 Administrateur</span>
        <a href="../logout.php" class="btn-nav">Déconnexion</a>
    </div>
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
        <a href="../index.php" target="_blank">🌐 &nbsp;Site public</a>
        <a href="../logout.php">🚪 &nbsp;Déconnexion</a>
    </aside>
    <main class="main-content">
        <div class="page-hdr">
            <h2>📦 Gestion des Expéditions</h2>
            <a href="ajouter.php" class="btn btn-primary">+ Nouvelle expédition</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert-success">✅ Opération effectuée avec succès.</div>
        <?php endif; ?>

        <div class="table-card">
            <div class="table-top">
                <h3>Liste des expéditions <span style="font-size:13px;color:var(--gray-text);font-weight:400">(<?= count($expeditions) ?>)</span></h3>
                <form method="GET" style="display:flex;gap:8px;align-items:center">
                    <input type="text" name="q" id="liveSearch" class="search-input"
                           placeholder="🔍 Rechercher..."
                           value="<?= htmlspecialchars($search) ?>">
                    <?php if($search): ?>
                        <a href="liste.php" class="btn btn-secondary btn-sm">✕</a>
                    <?php endif; ?>
                </form>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Référence</th><th>Client</th><th>Trajet</th>
                        <th>Poids</th><th>Frais</th><th>Date</th><th>Statut</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($expeditions as $e):
                    $bc = $e['statut']==='Livrée' ? 'badge-green'
                        : ($e['statut']==='En cours de route' ? 'badge-blue' : 'badge-yellow');
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($e['reference']) ?></strong></td>
                    <td>
                        <span class="avatar"><?= strtoupper(substr($e['prenom'],0,1).substr($e['nom'],0,1)) ?></span>
                        &nbsp;<?= htmlspecialchars($e['prenom'].' '.$e['nom']) ?>
                    </td>
                    <td><?= htmlspecialchars($e['ville_depart']).' → '.htmlspecialchars($e['ville_arrivee']) ?></td>
                    <td><?= $e['poids'] ?> kg</td>
                    <td><?= number_format($e['frais_transport'],2) ?> MAD</td>
                    <td><?= date('d/m/Y',strtotime($e['date_depart'])) ?></td>
                    <td><span class="badge <?= $bc ?>"><?= $e['statut'] ?></span></td>
                    <td>
                        <a href="modifier.php?id=<?= $e['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                        <a href="supprimer.php?id=<?= $e['id'] ?>" class="btn btn-danger btn-sm"
                           data-confirm="Supprimer <?= htmlspecialchars($e['reference']) ?> ?">🗑️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($expeditions)): ?>
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--gray-text)">Aucune expédition trouvée.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<script src="../script.js"></script>
</body>
</html>
