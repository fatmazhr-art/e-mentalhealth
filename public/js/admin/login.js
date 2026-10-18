/**
 * CURHATIN Admin — Login Page Scripts
 * ====================================
 * Fitur:
 * 1. Toggle password visibility (show/hide)
 * 2. Loading spinner saat form submit
 */

document.addEventListener('DOMContentLoaded', function () {

    // ---- Toggle Password Visibility ----
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('kata_sandi');

    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function () {
            const icon = this.querySelector('.material-icons-round');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.textContent = 'visibility';
            } else {
                passwordInput.type = 'password';
                icon.textContent = 'visibility_off';
            }
        });
    }

    // ---- Loading State on Form Submit ----
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('loginSubmitBtn');

    if (loginForm && submitBtn) {
        loginForm.addEventListener('submit', function () {
            submitBtn.classList.add('is-loading');
            submitBtn.disabled = true;
        });
    }

});
