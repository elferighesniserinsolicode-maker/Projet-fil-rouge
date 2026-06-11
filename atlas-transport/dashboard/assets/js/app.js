// =============================================
// Atlas Transport — interactions front-end
// =============================================

function openModal(id) {
    const m = document.getElementById(id);
    if (m) m.classList.add('open');
}
function closeModal(id) {
    const m = document.getElementById(id);
    if (m) m.classList.remove('open');
}

// Fermer la modale en cliquant sur l'arrière-plan
document.addEventListener('click', (e) => {
    if (e.target.classList && e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('open');
    }
});
// Fermer avec Échap
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.open').forEach((m) => m.classList.remove('open'));
    }
});

/* ---------- Clients ---------- */
function openClientModal() {
    document.getElementById('clientModalTitle').textContent = 'Nouveau Client';
    document.getElementById('clientAction').value = 'create';
    document.getElementById('clientId').value = '';
    document.getElementById('clientPrenom').value = '';
    document.getElementById('clientNom').value = '';
    document.getElementById('clientTel').value = '';
    document.getElementById('clientEmail').value = '';
    document.getElementById('clientAdresse').value = '';
    openModal('clientModal');
}

function editClient(btn) {
    const data = JSON.parse(btn.closest('tr').dataset.json);
    document.getElementById('clientModalTitle').textContent = 'Modifier Client';
    document.getElementById('clientAction').value = 'update';
    document.getElementById('clientId').value = data.id;
    document.getElementById('clientPrenom').value = data.prenom || '';
    document.getElementById('clientNom').value = data.nom || '';
    document.getElementById('clientTel').value = data.telephone || '';
    document.getElementById('clientEmail').value = data.email || '';
    document.getElementById('clientAdresse').value = data.adresse || '';
    openModal('clientModal');
}

/* ---------- Expéditions ---------- */
function openExpModal() {
    document.getElementById('expModalTitle').textContent = 'Nouvelle Expédition';
    document.getElementById('expAction').value = 'create';
    document.getElementById('expId').value = '';
    document.getElementById('expRef').value = '';
    document.getElementById('expDepart').value = '';
    document.getElementById('expArrivee').value = '';
    document.getElementById('expPoids').value = '';
    document.getElementById('expFrais').value = '';
    document.getElementById('expDate').value = '';
    document.getElementById('expStatut').value = 'En attente';
    openModal('expModal');
}

function editExp(btn) {
    const d = JSON.parse(btn.closest('tr').dataset.json);
    document.getElementById('expModalTitle').textContent = 'Modifier Expédition';
    document.getElementById('expAction').value = 'update';
    document.getElementById('expId').value = d.id;
    document.getElementById('expRef').value = d.reference || '';
    document.getElementById('expClient').value = d.client_id || '';
    document.getElementById('expDepart').value = d.ville_depart || '';
    document.getElementById('expArrivee').value = d.ville_arrivee || '';
    document.getElementById('expPoids').value = d.poids || '';
    document.getElementById('expFrais').value = d.frais_transport || '';
    document.getElementById('expDate').value = d.date_depart || '';
    document.getElementById('expStatut').value = d.statut || 'En attente';
    openModal('expModal');
}

/* ---------- Recherche dans les tableaux ---------- */
function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    if (!input || !table) return;
    input.addEventListener('input', () => {
        const q = input.value.toLowerCase();
        table.querySelectorAll('tbody tr').forEach((tr) => {
            tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
}
filterTable('searchClients', 'clientsTable');
filterTable('searchExp', 'expTable');

/* ---------- FAB ---------- */
document.getElementById('fab')?.addEventListener('click', () => {
    if (document.getElementById('expModal')) return openExpModal();
    if (document.getElementById('clientModal')) return openClientModal();
    window.location.href = 'expeditions.php';
});