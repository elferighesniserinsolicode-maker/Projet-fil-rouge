<?php
require_once __DIR__ . '/includes/functions.php';

$expeditions        = getExpeditions($pdo);
$clients            = getClients($pdo);
$recentExpeditions  = array_slice($expeditions, 0, 3);

// Bottom stats
$totalExp  = count($expeditions);
$inTransit = count(array_filter($expeditions, fn($e) => $e['statut'] === 'En cours de route'));
$delivered = count(array_filter($expeditions, fn($e) => $e['statut'] === 'Livrée'));
$tauxLiv   = $totalExp > 0 ? round(($delivered / $totalExp) * 100, 1) : 0;
$revenus   = array_sum(array_column($expeditions, 'frais_transport'));

$page  = 'expeditions';
$title = 'Expéditions — Atlas Transport Maroc';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sidebar.php';
?>
<div class="main">

    <!-- ── Topbar ── -->
    <div class="topbar">
        <h2>Gestion des Expéditions</h2>
        <div>
            <button class="btn-admin">Espace Admin</button>
        </div>
    </div>

    <!-- ── Page body ── -->
    <div class="exp-body">

        <!-- ── Two-column layout ── -->
        <div class="exp-layout">

            <!-- LEFT: form card -->
            <div class="exp-form-card">
                <h3 class="exp-form-title">Enregistrer l'expédition</h3>
                <form method="post" action="actions/expeditions_action.php" id="expCreateForm">
                    <input type="hidden" name="action" id="expAction" value="create">
                    <input type="hidden" name="id"     id="expId">

                    <div class="exp-field">
                        <label>RÉFÉRENCE</label>
                        <input type="text" name="reference" id="expRef" placeholder="EXP-2024-CAS" required>
                    </div>

                    <div class="exp-field">
                        <label>CLIENT</label>
                        <div class="exp-select-wrap">
                            <select name="client_id" id="expClient" required>
                                <option value="" disabled selected>Sélectionner un client…</option>
                                <?php foreach ($clients as $c): ?>
                                <option value="<?= (int) $c['id'] ?>"><?= e($c['prenom']) ?> <?= e($c['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <svg class="sel-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </div>

                    <div class="exp-row">
                        <div class="exp-field">
                            <label>DÉPART</label>
                            <input type="text" name="ville_depart" id="expDepart" placeholder="Casablanca" required>
                        </div>
                        <div class="exp-field">
                            <label>ARRIVÉE</label>
                            <input type="text" name="ville_arrivee" id="expArrivee" placeholder="Tanger" required>
                        </div>
                    </div>

                    <div class="exp-row">
                        <div class="exp-field">
                            <label>POIDS (KG)</label>
                            <input type="number" step="0.01" name="poids" id="expPoids" placeholder="500">
                        </div>
                        <div class="exp-field">
                            <label>COÛT (MAD)</label>
                            <input type="number" step="0.01" name="frais_transport" id="expFrais" placeholder="1 250.00">
                        </div>
                    </div>

                    <div class="exp-field">
                        <label>DATE D'EXPÉDITION</label>
                        <input type="date" name="date_depart" id="expDate">
                    </div>

                    <!-- statut hidden — set on create, editable in modal -->
                    <input type="hidden" name="statut" value="En attente">

                    <button type="submit" class="exp-submit-btn">Confirmer l'expédition</button>
                </form>
            </div>

            <!-- RIGHT: recent expeditions -->
            <div class="exp-right-col">
                <div class="exp-recent-head">
                    <h3>Expéditions Récentes</h3>
                    <!-- FIX: corrected typo "doir tout" → "Voir tout" -->
                    <a href="?view=all" class="exp-voir-tout">Voir tout ›</a>
                </div>

                <div class="exp-recent-list">
                    <?php foreach ($recentExpeditions as $exp):
                        $badgeClass = statutClass($exp['statut']);
                        $badgeLabel = statutLabel($exp['statut']);
                        $frais      = number_format($exp['frais_transport'], 0, ',', ' ');
                        $poids      = $exp['poids'] >= 1000
                            ? rtrim(rtrim(number_format($exp['poids'] / 1000, 1), '0'), '.') . ' Tonnes'
                            : number_format($exp['poids'], 0, ',', ' ') . ' KG';
                        $dateStr = $exp['date_depart'] ? date('d/m/Y', strtotime($exp['date_depart'])) : '—';
                        $heureStr = $exp['date_depart'] ? date('H:i', strtotime($exp['date_depart'])) : '—';
                    ?>
                    <div class="exp-recent-item">
                        <div class="exp-truck-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13"/>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                                <circle cx="5.5" cy="18.5" r="2.5"/>
                                <circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                        </div>
                        <div class="exp-recent-info">
                            <strong>#<?= e($exp['reference']) ?></strong>
                            <span><?= e($exp['ville_depart']) ?> → <?= e($exp['ville_arrivee']) ?></span>
                            <span>Poids: <?= $poids ?></span>
                        </div>
                        <div class="exp-recent-right">
                            <span class="exp-recent-cost"><?= $frais ?> MAD</span>
                            <span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span>
                            <span class="exp-recent-meta">
                                <?php if ($exp['statut'] === 'Livrée'): ?>
                                    Aujourd'hui, <?= date('H:i') ?>
                                <?php elseif ($exp['statut'] === 'En cours de route'): ?>
                                    Départ: <?= $heureStr ?>
                                <?php else: ?>
                                    ETA: <?= $dateStr ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if (empty($recentExpeditions)): ?>
                    <p style="text-align:center;color:var(--text-muted);padding:28px;font-size:.9rem;">Aucune expédition récente.</p>
                    <?php endif; ?>
                </div>

                <!-- Tracking banner -->
                <div class="exp-tracking-banner">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <circle cx="12" cy="12" r="4"/>
                        <line x1="4.93" y1="4.93" x2="9.17" y2="9.17"/>
                        <line x1="14.83" y1="14.83" x2="19.07" y2="19.07"/>
                        <line x1="14.83" y1="9.17" x2="19.07" y2="4.93"/>
                        <line x1="4.93" y1="19.07" x2="9.17" y2="14.83"/>
                    </svg>
                    <span>Suivi temps réel activé sur l'axe Casablanca - Tanger</span>
                </div>
            </div>
        </div>

        <!-- ── Stats bar ── -->
        <div class="exp-stats-bar">
            <div class="exp-stat">
                <span class="exp-stat-label">TOTAL EXPÉDITIONS</span>
                <span class="exp-stat-value"><?= number_format($totalExp, 0, ',', ' ') ?></span>
            </div>
            <div class="exp-stat">
                <span class="exp-stat-label">EN TRANSIT</span>
                <span class="exp-stat-value exp-stat-orange"><?= $inTransit ?></span>
            </div>
            <div class="exp-stat">
                <span class="exp-stat-label">TAUX DE LIVRAISON</span>
                <span class="exp-stat-value exp-stat-green"><?= $tauxLiv ?>%</span>
            </div>
            <div class="exp-stat">
                <!-- FIX: label was "REVENUS (MOINS)" — corrected to "REVENUS (MAD)" -->
                <span class="exp-stat-label">REVENUS (MAD)</span>
                <span class="exp-stat-value exp-stat-blue"><?= formatMontant($revenus) ?></span>
            </div>
        </div>

    </div><!-- /.exp-body -->

    <!-- ── Footer ── -->
    <footer class="footer">
        <span class="footer-brand">Atlas Transport Maroc</span>
        <nav class="footer-links">
            <a href="#">Contact</a>
            <a href="#">Réseau</a>
            <a href="#">Tarifs</a>
        </nav>
        <span class="footer-copy">© <?= date('Y') ?> Atlas Transport Maroc. Tous réservés.</span>
    </footer>

</div><!-- /.main -->

<!-- ── Edit modal ── -->
<div class="modal-overlay" id="expModal">
    <div class="modal">
        <h3>Modifier Expédition</h3>
        <form method="post" action="actions/expeditions_action.php">
            <input type="hidden" name="action" id="expEditAction" value="update">
            <input type="hidden" name="id"     id="expEditId">
            <div class="form-row">
                <div class="form-group">
                    <label>Référence</label>
                    <input type="text" name="reference" id="expEditRef" required>
                </div>
                <div class="form-group">
                    <label>Client</label>
                    <select name="client_id" id="expEditClient" required>
                        <?php foreach ($clients as $c): ?>
                        <option value="<?= (int) $c['id'] ?>"><?= e($c['prenom']) ?> <?= e($c['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Départ</label><input type="text" name="ville_depart" id="expEditDepart" required></div>
                <div class="form-group"><label>Arrivée</label><input type="text" name="ville_arrivee" id="expEditArrivee" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Poids (kg)</label><input type="number" step="0.01" name="poids" id="expEditPoids"></div>
                <div class="form-group"><label>Frais (MAD)</label><input type="number" step="0.01" name="frais_transport" id="expEditFrais"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Date</label><input type="date" name="date_depart" id="expEditDate"></div>
                <div class="form-group">
                    <label>Statut</label>
                    <select name="statut" id="expEditStatut">
                        <option value="En attente">En attente</option>
                        <option value="En cours de route">En cours de route</option>
                        <option value="Livrée">Livrée</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('expModal')">Annuler</button>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<?php if (isset($_GET['view']) && $_GET['view'] === 'all'): ?>
<div class="exp-all-overlay">
    <div class="exp-all-panel">
        <div class="exp-all-head">
            <h3>Toutes les Expéditions (<?= count($expeditions) ?>)</h3>
            <a href="expeditions.php" class="exp-close-btn">✕ Fermer</a>
        </div>
        <div class="exp-all-search">
            <input type="text" id="searchExp" placeholder="Rechercher une expédition…" oninput="filterExpTable()">
        </div>
        <table class="table" id="expTable">
            <thead>
                <tr>
                    <th>Référence</th><th>Client</th><th>Trajet</th><th>Poids</th>
                    <th>Frais</th><th>Date</th><th>Statut</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expeditions as $exp): ?>
                <tr data-json='<?= e(json_encode($exp)) ?>'>
                    <td class="ref">#<?= e($exp['reference']) ?></td>
                    <td><?= e($exp['prenom']) ?> <?= e($exp['nom']) ?></td>
                    <td class="trajet"><?= e($exp['ville_depart']) ?> → <?= e($exp['ville_arrivee']) ?></td>
                    <td class="trajet"><?= e(number_format($exp['poids'], 0, ',', ' ')) ?> kg</td>
                    <td class="trajet"><?= e(number_format($exp['frais_transport'], 0, ',', ' ')) ?> MAD</td>
                    <td class="date-cell"><?= $exp['date_depart'] ? e(date('d/m/Y', strtotime($exp['date_depart']))) : '—' ?></td>
                    <td><span class="badge <?= statutClass($exp['statut']) ?>"><?= statutLabel($exp['statut']) ?></span></td>
                    <td style="white-space:nowrap;">
                        <button class="btn-edit" onclick="editExpModal(this)">Modifier</button>
                        <a class="btn-danger" href="actions/expeditions_action.php?action=delete&id=<?= (int) $exp['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($expeditions)): ?>
                <tr><td colspan="8" class="empty-row">Aucune expédition.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- ── FAB ── -->
<button class="fab" id="fab" title="Nouvelle expédition">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
    </svg>
</button>

<script>
function openModal(id)  { document.getElementById(id)?.classList.add('open'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('open'); }

document.addEventListener('click', e => {
    if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('open');
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape')
        document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
});

function editExpModal(btn) {
    const d = JSON.parse(btn.closest('tr').dataset.json);
    document.getElementById('expEditAction').value  = 'update';
    document.getElementById('expEditId').value      = d.id;
    document.getElementById('expEditRef').value     = d.reference     || '';
    document.getElementById('expEditClient').value  = d.client_id     || '';
    document.getElementById('expEditDepart').value  = d.ville_depart  || '';
    document.getElementById('expEditArrivee').value = d.ville_arrivee || '';
    document.getElementById('expEditPoids').value   = d.poids         || '';
    document.getElementById('expEditFrais').value   = d.frais_transport || '';
    document.getElementById('expEditDate').value    = d.date_depart   || '';
    document.getElementById('expEditStatut').value  = d.statut        || 'En attente';
    openModal('expModal');
}

function filterExpTable() {
    const q = document.getElementById('searchExp').value.toLowerCase();
    document.querySelectorAll('#expTable tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

document.getElementById('fab')?.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    document.getElementById('expRef')?.focus();
});
</script>