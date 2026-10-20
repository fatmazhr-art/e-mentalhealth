@extends('admin.layouts.base')

@section('title', 'Buat Kata Sandi Baru — CURHATIN Admin')
@section('meta_description', 'Tentukan kata sandi baru untuk akun admin CURHATIN')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">
@endpush

@section('body')
<div class="login-page">

    <!-- Top Navbar -->
    <nav class="login-navbar" role="navigation" aria-label="Admin Navigation">
        <div class="login-navbar__logo">
            <img src="{{ asset('images/curhatin-logo.png') }}" alt="Logo CURHATIN" class="login-navbar__logo-img">
            <span class="login-navbar__brand">CURHATIN</span>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="login-container">
        <div class="login-card">

            <!-- Step Progress Indicator -->
            <div class="auth-steps">
                <div class="auth-step is-complete">
                    <div class="auth-step__circle">
                        <span class="material-icons-round">check</span>
                    </div>
                    <span class="auth-step__label">Email</span>
                </div>
                <div class="auth-step__line is-complete"></div>
                <div class="auth-step is-complete">
                    <div class="auth-step__circle">
                        <span class="material-icons-round">check</span>
                    </div>
                    <span class="auth-step__label">Verifikasi OTP</span>
                </div>
                <div class="auth-step__line is-complete"></div>
                <div class="auth-step is-active">
                    <div class="auth-step__circle">3</div>
                    <span class="auth-step__label">Sandi Baru</span>
                </div>
            </div>

            <!-- Card Header -->
            <div class="login-card__header">
                <div class="auth-card-badge">
                    <span class="material-icons-round">vpn_key</span>
                </div>
                <h1 class="login-card__title">Buat Sandi Baru</h1>
                <p class="login-card__subtitle">
                    Silakan tentukan kata sandi baru untuk akun admin:<br>
                    <span class="auth-email-badge">
                        <span class="material-icons-round">verified_user</span>
                        <strong>{{ $email }}</strong>
                    </span>
                </p>
            </div>

            <!-- Flash Alerts -->
            @include('admin.partials.alerts')

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('admin.password.update') }}" id="resetPasswordForm" autocomplete="off">
                @csrf

                <!-- Password Baru -->
                <div class="login-form__group">
                    <label for="kata_sandi" class="login-form__label">Kata Sandi Baru</label>
                    <div class="login-form__input-wrapper">
                        <span class="login-form__input-icon material-icons-round">lock_outline</span>
                        <input
                            type="password"
                            id="kata_sandi"
                            name="kata_sandi"
                            class="login-form__input @error('kata_sandi') is-invalid @enderror"
                            placeholder="Minimal 6 karakter"
                            required
                            autocomplete="new-password"
                            autofocus
                        >
                        <button type="button" class="login-form__toggle-password" id="togglePassword1" aria-label="Toggle password visibility">
                            <span class="material-icons-round">visibility_off</span>
                        </button>
                    </div>
                    @error('kata_sandi')
                        <span class="login-form__error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="login-form__group">
                    <label for="kata_sandi_confirmation" class="login-form__label">Konfirmasi Kata Sandi Baru</label>
                    <div class="login-form__input-wrapper">
                        <span class="login-form__input-icon material-icons-round">lock_outline</span>
                        <input
                            type="password"
                            id="kata_sandi_confirmation"
                            name="kata_sandi_confirmation"
                            class="login-form__input"
                            placeholder="Ulangi kata sandi baru"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="login-form__toggle-password" id="togglePassword2" aria-label="Toggle password confirmation visibility">
                            <span class="material-icons-round">visibility_off</span>
                        </button>
                    </div>
                </div>

                <div class="auth-hint-box">
                    <span class="material-icons-round">info</span>
                    <span>Pastikan kata sandi minimal 6 karakter dengan kombinasi yang aman.</span>
                </div>

                <button type="submit" class="login-form__submit" id="resetSubmitBtn">
                    <span class="login-form__submit-text">Perbarui Kata Sandi</span>
                    <div class="login-form__spinner"></div>
                </button>

                <div class="auth-footer-action">
                    <a href="{{ route('admin.login') }}" class="auth-back-link">
                        <span class="material-icons-round">arrow_back</span>
                        <span>Batal dan Kembali ke Halaman Masuk</span>
                    </a>
                </div>
            </form>

        </div>
    </main>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Password toggles
    function setupPasswordToggle(btnId, inputId) {
        const btn = document.getElementById(btnId);
        const input = document.getElementById(inputId);
        if (btn && input) {
            btn.addEventListener('click', function () {
                const icon = this.querySelector('.material-icons-round');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility_off';
                }
            });
        }
    }

    setupPasswordToggle('togglePassword1', 'kata_sandi');
    setupPasswordToggle('togglePassword2', 'kata_sandi_confirmation');

    // Submit loading state
    const form = document.getElementById('resetPasswordForm');
    const submitBtn = document.getElementById('resetSubmitBtn');
    if (form && submitBtn) {
        form.addEventListener('submit', function () {
            submitBtn.classList.add('is-loading');
            submitBtn.disabled = true;
        });
    }
});
</script>
@endpush
