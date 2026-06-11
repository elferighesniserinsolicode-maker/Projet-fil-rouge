document.addEventListener('DOMContentLoaded', function () {

    // 1. Cacher automatiquement le message de succès après 3 secondes
    var alertSuccess = document.querySelector('.alert-success');
    if (alertSuccess) {
        setTimeout(function () {
            // Ajouter une transition douce pour effacer le message
            alertSuccess.style.transition = 'opacity 0.6s ease';
            alertSuccess.style.opacity = '0';
            
            // Supprimer définitivement le message de la page après l'animation
            setTimeout(function () { alertSuccess.remove(); }, 600);
        }, 3000); // 3000 millisecondes = 3 secondes
    }

    // 2. Recherche instantanée (en temps réel) dans le tableau
    var searchInput = document.getElementById('liveSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            // Récupérer le texte tapé et le mettre en minuscules
            var val = this.value.toLowerCase();
            
            // Parcourir toutes les lignes (tr) du tableau
            document.querySelectorAll('tbody tr').forEach(function (row) {
                // Si la ligne contient le texte recherché, on l'affiche, sinon on la cache
                row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
            });
        });
    }

    // 3. Demander une confirmation avant de supprimer (Sécurité)
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            // Si l'utilisateur clique sur "Annuler", on bloque la suppression
            if (!confirm(this.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

});