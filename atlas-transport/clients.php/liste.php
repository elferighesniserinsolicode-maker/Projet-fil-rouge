<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: ../connexion.php");
require '../config.php';

$clients = $pdo->query("SELECT * FROM clients ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">🚚 Atlas Transport</div>
        <a href="liste.php" class="sidebar-item active">👥 Clients</a>
        <a href="../expeditions/liste.php" class="sidebar-item">📦 Expéditions</a>
        <a href="../logout.php" class="sidebar-item">🚪 Déconnexion</a>
    </aside>

    <!-- MAIN -->
    <main class="main">
        <div class="page-header">
            <h1>Gestion des Clients</h1>
            <a href="ajouter.php" class="btn-primary">+ Ajouter client</a>
        </div>

        <!-- KPIs -->
        <div class="kpi-grid">
            <div class="kpi"><div class="kpi-val"><?= count($clients) ?></div><div class="kpi-lbl">Total clients</div></div>
        </div>

        <!-- TABLE -->
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th><th>Prénom</th><th>Téléphone</th>
                        <th>Email</th><th>Adresse</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['nom']) ?></td>
                        <td><?= htmlspecialchars($c['prenom']) ?></td>
                        <td><?= htmlspecialchars($c['telephone']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['adresse']) ?></td>
                        <td>
                            <a href="modifier.php?id=<?= $c['id'] ?>" class="btn-edit">✏️ Modifier</a>
                            <a href="supprimer.php?id=<?= $c['id'] ?>" 
                               onclick="return confirm('Supprimer ce client ?')" 
                               class="btn-delete">🗑️ Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>