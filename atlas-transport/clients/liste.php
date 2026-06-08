<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../connexion.php"); exit; }
require '../db.php';

$search = trim($_GET['q'] ?? '');
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE nom LIKE ? OR prenom LIKE ? OR telephone LIKE ? OR email LIKE ? ORDER BY id DESC");
    $like = "%$search%";
    $stmt->execute([$like,$like,$like,$like]);
} else {
    $stmt = $pdo->query("SELECT * FROM clients ORDER BY id DESC");
}
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients — Atlas Transport</title>
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
        <a href="liste.php" class="active">👥 &nbsp;Clients</a>
        <a href="../expeditions/liste.php">📦 &nbsp;Expéditions</a>
        <div class="sidebar-section">Compte</div>
        <a href="../index.php" target="_blank">🌐 &nbsp;Site public</a>
        <a href="../logout.php">🚪 &nbsp;Déconnexion</a>
    </aside>

    <main class="main-content">
        <div class="page-hdr">
            <h2>👥 Gestion des Clients</h2>
            <a href="ajouter.php" class="btn btn-primary">+ Ajouter un client</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert-success">✅ Opération effectuée avec succès.</div>
        <?php endif; ?>

        <div class="table-card">
            <div class="table-top">
                <h3>Liste des clients <span style="font-size:13px;color:var(--gray-text);font-weight:400">(<?= count($clients) ?>)</span></h3>
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
                        <th>#</th><th>Client</th><th>Téléphone</th>
                        <th>Email</th><th>Adresse</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($clients as $c): ?>
                <tr>
                    <td style="color:var(--gray-text)"><?= $c['id'] ?></td>
                    <td>
                        <span class="avatar"><?= strtoupper(substr($c['prenom'],0,1).substr($c['nom'],0,1)) ?></span>
                        &nbsp;<strong><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></strong>
                    </td>
                    <td><?= htmlspecialchars($c['telephone']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= htmlspecialchars($c['adresse']) ?></td>
                    <td>
                        <a href="modifier.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                        <a href="supprimer.php?id=<?= $c['id'] ?>" class="btn btn-danger btn-sm"
                           data-confirm="Supprimer <?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?> ?">🗑️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($clients)): ?>
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--gray-text)">Aucun client trouvé.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
<script src="../script.js"></script>
</body>
</html>
