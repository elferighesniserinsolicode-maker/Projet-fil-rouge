<?php
require 'db.php';

$expedition = null;
$erreur     = "";

if (!empty($_GET['reference'])) {
    $ref  = htmlspecialchars(trim($_GET['reference']));
    $stmt = $pdo->prepare("
        SELECT e.*, c.nom, c.prenom
        FROM expeditions e
        JOIN clients c ON e.client_id = c.id
        WHERE e.reference = ?
    ");
    $stmt->execute([$ref]);
    $expedition = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$expedition)
        $erreur = "Aucune expédition trouvée pour la référence : <strong>$ref</strong>";
}

$total_exp     = $pdo->query("SELECT COUNT(*) FROM expeditions")->fetchColumn();
$total_clients = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atlas Transport Maroc</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="index.php" class="brand">🚚 Atlas Transport Maroc</a>
    <div class="nav-links">
        <a href="index.php">Accueil</a>
        <a href="#suivi">Suivi</a>
        <a href="contact.php">Contact</a>
        <a href="connexion.php" class="btn-nav">Espace Admin</a>
    </div>
</nav>

<!-- HERO + TRACKING -->
<section class="hero" id="suivi">
    <div class="hero-left">
        <h1>Logistique Fiable &amp; Précise<br>
            Suivi des Expéditions<br>
            <span>Routières</span> au Maroc
        </h1>
        <p>Gérez vos marchandises en temps réel sur tout le territoire marocain.</p>
        <div class="track-box">
            <label>Entrez la référence de votre expédition :</label>
            <form method="GET" action="index.php">
                <div class="track-row">
                    <input type="text" name="reference"
                           placeholder="Ex: EXP-2024-001"
                           value="<?= htmlspecialchars($_GET['reference'] ?? '') ?>">
                    <button type="submit">Suivre →</button>
                </div>
            </form>
        </div>
    </div>
    <div class="hero-right">
        <img src="assets/boutha.png">
    </div>
</section>

<!-- RÉSULTAT TRACKING -->
<?php if ($expedition): ?>
<section class="result-section">
    <h2>✅ Résultat du suivi</h2>
    <div class="result-card">
        <?php
        $badge = $expedition['statut'] === 'Livrée' ? 'badge-green'
               : ($expedition['statut'] === 'En cours de route' ? 'badge-blue' : 'badge-yellow');
        $rows = [
            'Référence'          => htmlspecialchars($expedition['reference']),
            'Client'             => htmlspecialchars($expedition['prenom'].' '.$expedition['nom']),
            'Ville de départ'    => htmlspecialchars($expedition['ville_depart']),
            "Ville d'arrivée"    => htmlspecialchars($expedition['ville_arrivee']),
            'Poids'              => $expedition['poids'].' kg',
            'Frais de transport' => number_format($expedition['frais_transport'],2).' MAD',
            'Date de départ'     => date('d/m/Y', strtotime($expedition['date_depart'])),
            'Statut'             => '<span class="badge '.$badge.'">'.$expedition['statut'].'</span>',
        ];
        foreach ($rows as $lbl => $val):
        ?>
        <div class="result-row">
            <span class="lbl"><?= $lbl ?></span>
            <span class="val"><?= $val ?></span>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php elseif ($erreur): ?>
<section class="result-section">
    <div class="alert-error">❌ <?= $erreur ?></div>
</section>
<?php endif; ?>

<!-- STATS -->
<section class="stats-section">
    <div class="stat-item">
        <div class="num"><?= $total_exp ?></div>
        <div class="lbl">Expéditions livrées</div>
    </div>
    <div class="stat-item">
        <div class="num"><?= $total_clients ?></div>
        <div class="lbl">Clients actifs</div>
    </div>
    <div class="stat-item">
        <div class="num">15</div>
        <div class="lbl">Villes desservies</div>
    </div>
</section>

<!-- SUPPORT -->
<section class="support-section" id="contact">
    <div class="support-box">
        <h3>📞 Support 24/7</h3>
            <p>Notre équipe est disponible à tout moment.</p>
        <div class="phone">+212 6 12 34 56 78</div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div>
        <div class="footer-brand">🚚 Atlas Transport Maroc</div>
        <div class="footer-desc">Suivi des Expéditions Routières au Maroc</div>
    </div>
    <div class="footer-links">
        <a href="index.php">Accueil</a>
        <a href="contact.php">Contact</a>
        <a href="#">Aide</a>
    </div>
    <div class="footer-copy">© 2026 Atlas Transport Maroc</div>
</footer>

<script src="script.js"></script>
</body>
</html>
