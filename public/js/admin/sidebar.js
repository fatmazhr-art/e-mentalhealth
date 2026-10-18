/**
 * CURHATIN Admin — Sidebar Toggle (Mobile)
 * ==========================================
 * Menangani buka/tutup sidebar di layar kecil (<900px).
 */

document.addEventListener('DOMContentLoaded', function () {

    const sidebar   = document.getElementById('adminSidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    const hamburger = document.getElementById('hamburgerBtn');

    if (hamburger) {
        hamburger.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    }

});
