// ===== FILTRES DYNAMIQUES MENUS =====
document.addEventListener('DOMContentLoaded', function() {

    const filtreForm = document.getElementById('filtres-form');
    const menusContainer = document.getElementById('menus-container');

    if (filtreForm && menusContainer) {
        const inputs = filtreForm.querySelectorAll('select, input');

        inputs.forEach(function(input) {
            input.addEventListener('change', function() {
                const formData = new FormData(filtreForm);
                const params = new URLSearchParams(formData);

                fetch('/menus?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    menusContainer.innerHTML = data.html;
                })
                .catch(error => console.error('Erreur filtres:', error));
            });
        });
    }
});