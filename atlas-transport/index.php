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
    if (!$expedition) {
        $erreur = "Aucune expédition trouvée pour la référence : <strong>$ref</strong>";
    }
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

<nav class="navbar">
    <div class="nav-container">
        <div class="brand">
            <span class="logo-icon">▲</span>
            <span class="logo-text">Atlas Transport Maroc</span>
        </div>
        <div class="nav-links">
            <a href="index.php" class="active">Accueil</a>
            <a href="#suivi">Suivi</a>
            <a href="#clients">Clients</a>
            <a href="#contact">Contact</a>
        </div>
        <div class="nav-actions">
            <a href="connexion.php" class="btn-admin">Espace Admin</a>
        </div>
    </div>
</nav>

<main class="main-wrapper">
    
    <section class="hero-section">
        <div class="hero-content">
            <span class="subtitle">Logistique Nationale &amp; Précision</span>
            <h1>Suivi des Expéditions <br><span>Routières</span> au Maroc</h1>
            <p>Suivez votre expédition en temps réel entre les villes marocaines. Une visibilité totale de Tanger à Lagouira.</p>
            
            <div class="search-box">
                <form method="GET" action="index.php">
                    <label>Entrez votre référence (Ex: EXP001)</label>
                    <div class="form-group">
                        <input type="text" name="reference" required placeholder="EXP001..." value="<?= htmlspecialchars($_GET['reference'] ?? '') ?>">
                        <button type="submit">Rechercher</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&q=80&w=800" alt="Atlas Truck">
        </div>
    </section>

    <div class="content-grid" id="suivi">
        
        <div class="tracking-area">
            <?php if ($expedition): ?>
                <div class="tracking-card">
                    <div class="card-header">
                        <div class="header-info">
                            <h3>Suivi en direct: <?= htmlspecialchars($expedition['reference']) ?></h3>
                            <p class="meta-text">Dernière mise à jour: Il y a 5 minutes</p>
                        </div>
                        <div class="status-badge status-<?= strtolower(str_replace(' ', '-', $expedition['statut'])) ?>">
                            ● <?= htmlspecialchars($expedition['statut']) ?>
                        </div>
                    </div>

                    <div class="timeline">
                        <div class="timeline-item step-done">
                            <div class="icon-marker">✓</div>
                            <div class="step-details">
                                <h4><?= htmlspecialchars($expedition['ville_depart']) ?> <span class="label">(Origine)</span></h4>
                                <p class="time">Expédié le <?= date('d M, H:i', strtotime($expedition['date_depart'])) ?></p>
                            </div>
                        </div>

                        <?php if($expedition['statut'] !== 'En attente'): ?>
                        <div class="timeline-item step-active">
                            <div class="icon-marker">➔</div>
                            <div class="step-details">
                                <h4>Autoroute A1 <span class="label">(En transit)</span></h4>
                                <p class="desc">Passage en cours vers la destination</p>
                                
                                <div class="driver-box">
                                    <div class="driver-avatar">
                                        <?= strtoupper(substr($expedition['prenom'],0,1).substr($expedition['nom'],0,1)) ?>
                                    </div>
                                    <div class="driver-info">
                                        <span class="role">Chauffeur</span>
                                        <span class="name"><?= htmlspecialchars($expedition['prenom'].' '.$expedition['nom']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="timeline-item step-pending">
                            <div class="icon-marker">●</div>
                            <div class="step-details">
                                <h4><?= htmlspecialchars($expedition['ville_arrivee']) ?> <span class="label">(Destination)</span></h4>
                                <p class="desc">Arrivée estimée prochainement</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer-details">
                        <div class="detail-item">
                            <span class="title">Poids total:</span>
                            <span class="value"><?= $expedition['poids'] ?> kg</span>
                        </div>
                        <div class="detail-item">
                            <span class="title">Frais calculés:</span>
                            <span class="value"><?= number_format($expedition['frais_transport'], 2) ?> MAD</span>
                        </div>
                    </div>
                </div>

            <?php elseif ($erreur): ?>
                <div class="alert-error">
                    <span class="icon">❌</span>
                    <p><?= $erreur ?></p>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>Veuillez introduire un code d'expédition valide ci-dessus pour afficher l'état du transit.</p>
                </div>
            <?php endif; ?>
        </div>

        <aside class="sidebar-area" id="contact">
            <div class="support-card">
                <div class="support-icon">📞</div>
                <h3>Support 24/7</h3>
                <p>Nos experts logistiques sont à votre écoute pour toute demande urgente.</p>
                <div class="phone-number">
                    <a href="tel:+212612345678">+212 6 12 34 56 78</a>
                </div>
            </div>

            <div class="mini-stats-list">
                <div class="list-row">
                    <span>Villes Desservies</span>
                    <span class="badge">15+</span>
                </div>
                <div class="list-row" id="clients">
                    <span>Clients Actifs</span>
                    <span class="count"><?= $total_clients ?></span>
                </div>
                <div class="list-row">
                    <span>Taux de Livraison</span>
                    <span class="rate">99.9%</span>
                </div>
            </div>
        </aside>
    </div>

    <div class="counters-strip">
        <div class="counter-item">
            <div class="number"><?= 1200 + $total_exp ?></div>
            <div class="label">Expéditions Livrées</div>
        </div>
        <div class="counter-item">
            <div class="number"><?= $total_clients ?></div>
            <div class="label">Clients Actifs</div>
        </div>
        <div class="counter-item">
            <div class="number">15</div>
            <div class="label">Villes Desservies</div>
        </div>
    </div>
</main>

<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-info">
            <div class="brand-name">Atlas Transport Maroc</div>
            <p>Expertise logistique de pointe pour le marché marocain. Efficacité, sécurité et transparence.</p>
        </div>
        <div class="footer-links">
            <a href="#">Contact</a>
            <a href="#">Mentions Légales</a>
            <a href="#">Réseaux</a>
        </div>
        <div class="footer-copyright">
            &copy; 2026 Atlas Transport Maroc. Tous droits réservés.
        </div>
    </div>
</footer>

</body>
</html>