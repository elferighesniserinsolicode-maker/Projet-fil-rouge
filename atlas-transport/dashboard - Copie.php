<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: connexion.php"); exit; }
require 'db.php';

$nb_clients = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$nb_exp     = $pdo->query("SELECT COUNT(*) FROM expeditions")->fetchColumn();
$nb_attente = $pdo->query("SELECT COUNT(*) FROM expeditions WHERE statut='En attente'")->fetchColumn();
$nb_route   = $pdo->query("SELECT COUNT(*) FROM expeditions WHERE statut='En cours de route'")->fetchColumn();
$nb_livree  = $pdo->query("SELECT COUNT(*) FROM expeditions WHERE statut='Livrée'")->fetchColumn();

$recent = $pdo->query("
    SELECT e.*, c.nom, c.prenom
    FROM expeditions e
    JOIN clients c ON e.client_id = c.id
    ORDER BY e.id DESC LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Atlas Transport</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <a href="dashboard.php" class="brand">🚚 Atlas Transport Maroc</a>
    <div class="nav-links">
        <a href="index.php" target="_blank">🌐 Site public</a>
        <span style="color:rgba(255,255,255,0.65);font-size:13px">👤 Administrateur</span>
        <a href="logout.php" class="btn-nav">Déconnexion</a>
    </div>
</nav>

<div class="dash-layout">
    <aside class="sidebar">
        <div class="sidebar-logo">🚚 Atlas Transport</div>
        <div class="sidebar-section">Principal</div>
        <a href="dashboard.php" class="active">📊 &nbsp;Dashboard</a>
        <div class="sidebar-section">Gestion</div>
        <a href="clients/liste.php">👥 &nbsp;Clients</a>
        <a href="expeditions/liste.php">📦 &nbsp;Expéditions</a>
        <div class="sidebar-section">Compte</div>
        <a href="index.php" target="_blank">🌐 &nbsp;Site public</a>
        <a href="logout.php">🚪 &nbsp;Déconnexion</a>
    </aside>

    <main class="main-content">
        <div class="page-hdr">
            <h2>📊 Tableau de bord</h2>
            <span style="font-size:13px;color:var(--gray-text)">Bienvenue, Administrateur</span>
        </div>

        <!-- KPI -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div><div class="kpi-num"><?= $nb_clients ?></div><div class="kpi-lbl">Total Clients</div></div>
                <div class="kpi-icon">👥</div>
            </div>
            <div class="kpi-card">
                <div><div class="kpi-num"><?= $nb_exp ?></div><div class="kpi-lbl">Total Expéditions</div></div>
                <div class="kpi-icon">📦</div>
            </div>
            <div class="kpi-card">
                <div><div class="kpi-num"><?= $nb_attente ?></div><div class="kpi-lbl">En attente</div></div>
                <div class="kpi-icon">⏳</div>
            </div>
            <div class="kpi-card">
                <div><div class="kpi-num"><?= $nb_route ?></div><div class="kpi-lbl">En cours de route</div></div>
                <div class="kpi-icon">🚛</div>
            </div>
            <div class="kpi-card">
                <div><div class="kpi-num"><?= $nb_livree ?></div><div class="kpi-lbl">Livrées</div></div>
                <div class="kpi-icon">✅</div>
            </div>
        </div>

        <!-- TABLE RÉCENTE -->
        <div class="table-card">
            <div class="table-top">
                <h3>📋 Expéditions récentes</h3>
                <a href="expeditions/liste.php" class="btn btn-primary btn-sm">Voir tout →</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Trajet</th>
                        <th>Date départ</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($recent as $e):
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
                    <td><?= date('d/m/Y', strtotime($e['date_depart'])) ?></td>
                    <td><span class="badge <?= $bc ?>"><?= $e['statut'] ?></span></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script src="script.js"></script>
</body>
</html>
