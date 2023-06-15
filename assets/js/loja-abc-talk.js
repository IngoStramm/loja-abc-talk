document.addEventListener('DOMContentLoaded', function () {
    function toggleTabelaMedidas() {
        const toggleMedidas = document.querySelectorAll('.toggle-medidas');
        for (const el of toggleMedidas) {
            el.addEventListener('click', (e) => {
                e.preventDefault();
                if (el.classList.contains('visible')) {
                    el.classList.remove('visible');
                    el.nextElementSibling.style.display = 'none';
                } else {
                    el.classList.add('visible');
                    el.nextElementSibling.style.display = 'inline-block';
                }
            });
        }
    }
    toggleTabelaMedidas();
});