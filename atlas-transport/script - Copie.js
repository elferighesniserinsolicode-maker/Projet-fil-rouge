document.addEventListener('DOMContentLoaded', function () {

    // 1. إخفاء رسالة النجاح تلقائياً بعد 3 ثوان
    var alertSuccess = document.querySelector('.alert-success');
    if (alertSuccess) {
        setTimeout(function () {
            alertSuccess.style.transition = 'opacity 0.6s ease';
            alertSuccess.style.opacity = '0';
            setTimeout(function () { alertSuccess.remove(); }, 600);
        }, 3000);
    }

    // 2. بحث لحظي في الجداول
    var searchInput = document.getElementById('liveSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            var val = this.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
            });
        });
    }

    // 3. تأكيد الحذف
    document.querySelectorAll('[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(this.getAttribute('data-confirm'))) e.preventDefault();
        });
    });

});
