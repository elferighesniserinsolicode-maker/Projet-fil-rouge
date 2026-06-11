<?php

require_once __DIR__ . '/includes/functions.php';

$clients = getClients($pdo);
$stats = getStats($pdo);

$page = 'clients';
$title = 'Gestion Clients';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sidebar.php';
?>

<main class="main">

<h2>Gestion des Clients</h2>

<!-- STATS -->
<div class="stats">
    <div class="card">Clients: <?= count($clients) ?></div>
    <div class="card">Actifs: <?= $stats['clients_actifs'] ?? 0 ?></div>
</div>

<!--  ADD CLIENT -->
<section class="box">
    <h3>Ajouter Client</h3>

    <form method="post" action="actions/clients_action.php">
        <input type="hidden" name="action" value="create">

        <div class="grid">
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="telephone" placeholder="Téléphone">
            <input type="email" name="email" placeholder="Email">
            <input type="text" name="adresse" placeholder="Adresse">
        </div>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/style2.css">
        <button type="submit">+ Ajouter</button>
    </form>
</section>

<!-- 🔍 SEARCH -->
<input type="text" id="search" placeholder="Rechercher client...">

<!-- 📋 TABLE -->
<section class="box">

<table>
    <thead>
        <tr>
            <th>Client</th>
            <th>Contact</th>
            <th>Adresse</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody id="tableBody">
        <?php foreach ($clients as $c): ?>
        <tr>
            <td>
                <strong><?= $c['prenom'] . ' ' . $c['nom'] ?></strong>
            </td>

            <td>
                📞 <?= $c['telephone'] ?: '-' ?><br>
                ✉️ <?= $c['email'] ?: '-' ?>
            </td>

            <td>
                📍 <?= $c['adresse'] ?: '-' ?>
            </td>

            <td>
                <a href="edit.php?id=<?= $c['id'] ?>">✏️</a>
                <a href="actions/clients_action.php?action=delete&id=<?= $c['id'] ?>"
                   onclick="return confirm('Delete client?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</section>

</main>

<!--  SIMPLE JS SEARCH -->
<script>
document.getElementById('search').addEventListener('keyup', function () {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tableBody tr');

    rows.forEach(r => {
        r.style.display = r.innerText.toLowerCase().includes(value) ? '' : 'none';
    });
});
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>

*/