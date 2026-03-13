import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import Alpine from 'alpinejs';
import { initSelect2 } from './modules/select2';

import Swal from 'sweetalert2';
window.Swal = Swal;

import './modules/swal-global';
import './modules/incidencias';
window.Alpine = Alpine;
Alpine.start();

// Tooltips Bootstrap
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
        .forEach(el => new bootstrap.Tooltip(el));

    // Select2
    initSelect2();
});

// Sidebar Toggle
document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarMenu = document.getElementById('sidebarMenu');

    if (sidebarToggle && sidebarOverlay && sidebarMenu) {
        [sidebarToggle, sidebarOverlay].forEach(el => {
            el.addEventListener('click', () => {
                sidebarMenu.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        });
    }
});