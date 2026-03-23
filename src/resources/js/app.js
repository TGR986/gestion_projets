import './bootstrap';


import Alpine from 'alpinejs';


window.Alpine = Alpine;


Alpine.start();


document.querySelectorAll('tr[data-href]').forEach((row) => {
    row.addEventListener('click', (event) => {
        const interactiveElement = event.target.closest('a, button, form, input, select, textarea, label');


        if (interactiveElement) {
            return;
        }


        window.location = row.dataset.href;
    });
});
