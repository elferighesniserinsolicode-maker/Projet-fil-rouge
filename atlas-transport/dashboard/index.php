<?php
require_once __DIR__ . '/includes/functions.php';

$stats        = getStats($pdo);
$expeditions  = getExpeditionsRecentes($pdo, 5);

$page  = 'dashboard';
$title = 'Tableau de Bord — Atlas Transport Maroc';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sidebar.php';
?>
<main class="main">
    <div class="topbar">
        <h2>Tableau de Bord</h2>
        <div class="topbar-right">
            <span class="status-pill"><span class="dot"></span> Réseau Opérationnel</span>
            <button class="btn-admin">Espace Admin</button>
        </div>
    </div>

    <!-- Stat cards -->
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-head">
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="4" rx="1"/><path d="M5 8v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                </div>
                <span class="stat-tag tag-green">+12%</span>
            </div>
            <div class="stat-label">Total Expéditions</div>
            <div class="stat-value"><?= number_format($stats['total_expeditions'], 0, ',', ' ') ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-head">
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="3" width="16" height="18" rx="2"/><circle cx="12" cy="10" r="3"/><path d="M7 20a5 5 0 0 1 10 0"/></svg>
                </div>
                <span class="stat-tag tag-blue">Stable</span>
            </div>
            <div class="stat-label">Clients Actifs</div>
            <div class="stat-value"><?= number_format($stats['clients_actifs'], 0, ',', ' ') ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-head">
                <div class="stat-icon icon-orange">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><line x1="12" y1="11" x2="12" y2="15"/><line x1="12" y1="17" x2="12" y2="17"/></svg>
                </div>
                <span class="stat-tag tag-orange">Priorité</span>
            </div>
            <div class="stat-label">Livraisons en Attente</div>
            <div class="stat-value"><?= number_format($stats['en_attente'], 0, ',', ' ') ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-head">
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                </div>
                <span class="stat-tag tag-green">+8.4k MAD</span>
            </div>
            <div class="stat-label">Chiffre d'Affaires</div>
            <div class="stat-value"><?= formatMontant($stats['chiffre_affaires']) ?></div>
        </div>
    </section>

    <!-- Content row -->
    <section class="content-grid">
        <div class="panel">
            <div class="panel-head">
                <h3>Expéditions Récentes</h3>
                <a href="expeditions.php" class="link">Voir Tout</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Trajet</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($expeditions as $exp): ?>
                    <tr>
                        <td class="ref">#<?= e($exp['reference']) ?></td>
                        <td class="trajet"><?= e($exp['ville_depart']) ?> &rarr; <?= e($exp['ville_arrivee']) ?></td>
                        <td><span class="badge <?= statutClass($exp['statut']) ?>"><?= statutLabel($exp['statut']) ?></span></td>
                        <td class="date-cell"><?= e(date('d M', strtotime($exp['date_depart']))) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($expeditions)): ?>
                    <tr><td colspan="4" style="text-align:center;color:var(--text-muted);">Aucune expédition.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="side-col">
            <div class="panel">
                <div class="panel-head"><h3>Activité Réseau</h3></div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-dot dot-blue">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        </div>
                        <div>
                            <strong>Départ Camion #102</strong>
                            <span>Hub Tanger &bull; 14:20</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot dot-green">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div>
                            <strong>Livraison Terminée</strong>
                            <span>Client Agadir S.A &bull; 12:45</span>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot dot-slate">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <strong>Retard Signalé - Trafic</strong>
                            <span>Route A1 Tanger &bull; 10:15</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cta-card">
                <h3>Planifier Trajet</h3>
                <p>Optimiser les itinéraires entre les hubs régionaux.</p>
            </div>
        </div>
    </section>

<?php require __DIR__ . '/includes/footer.php'; ?>