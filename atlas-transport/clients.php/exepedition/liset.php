<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: ../connexion.php");
require '../config.php';

$clients = $pdo->query("SELECT * FROM clients ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // توليد مرجع تلقائي
    $ref = 'EXP-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
    $stmt = $pdo->prepare("INSERT INTO expeditions 
        (reference, client_id, ville_depart, ville_arrivee, poids, frais_transport, date_depart, statut) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $ref, $_POST['client_id'], $_POST['ville_depart'],
        $_POST['ville_arrivee'], $_POST['poids'],
        $_POST['frais_transport'], $_POST['date_depart'], $_POST['statut']
    ]);
    header("Location: liste.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Ajouter Expédition</title><link rel="stylesheet" href="../style.css"></head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <div class="sidebar-logo">🚚 Atlas Transport</div>
        <a href="../clients/liste.php" class="sidebar-item">👥 Clients</a>
        <a href="liste.php" class="sidebar-item active">📦 Expéditions</a>
        <a href="../logout.php" class="sidebar-item">🚪 Déconnexion</a>
    </aside>
    <main class="main">
        <div class="page-header">
            <h1>Nouvelle Expédition</h1>
            <a href="liste.php" class="btn-back">← Retour</a>
        </div>
        <div class="form-card">
            <form method="POST">
                <div class="form-group">
                    <label>Client</label>
                    <select name="client_id" required>
                        <option value="">-- Choisir un client --</option>
                        <?php foreach ($clients as $cl): ?>
                        <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['prenom'] . ' ' . $cl['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Ville de départ</label><input type="text" name="ville_depart" required></div>
                <div class="form-group"><label>Ville d'arrivée</label><input type="text" name="ville_arrivee" required></div>
                <div class="form-group"><label>Poids (kg)</label><input type="number" step="0.01" name="poids" required></div>
                <div class="form-group"><label>Frais de transport (MAD)</label><input type="number" step="0.01" name="frais_transport" required></div>
                <div class="form-group"><label>Date de départ</label><input type="date" name="date_depart" required></div>
                <div class="form-group">
                    <label>Statut</label>
                    <select name="statut">
                        <option value="En attente">En attente</option>
                        <option value="En cours de route">En cours de route</option>
                        <option value="Livrée">Livrée</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </form>
        </div>
    </main>
</div>
</body>
</html>