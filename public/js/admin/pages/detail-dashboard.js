/**
 * CURHATIN Admin - Detail Dashboard Script
 * Menangani interaksi toggle periode dan navigasi tab kategori
 */

document.addEventListener('DOMContentLoaded', function () {
    const periodButtons = document.querySelectorAll('.period-btn');

    periodButtons.forEach(button => {
        button.addEventListener('click', function () {
            const period = this.getAttribute('data-period');
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('periode', period);
            window.location.href = currentUrl.toString();
        });
    });
});
