@extends('admin.layouts.base')

@section('title', 'Lupa Kata Sandi — CURHATIN Admin')
@section('meta_description', 'Atur ulang kata sandi akun admin CURHATIN')

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
                <div class="auth-step is-active">
                    <div class="auth-step__circle">1</div>
                    <span class="auth-step__label">Email</span>
                </div>
                <div class="auth-step__line"></div>
                <div class="auth-step">
                    <div class="auth-step__circle">2</div>
                    <span class="auth-step__label">Verifikasi OTP</span>
                </div>
                <div class="auth-step__line"></div>
                <div class="auth-step">
                    <div class="auth-step__circle">3</div>
                    <span class="auth-step__label">Sandi Baru</span>
                </div>
            </div>

            <!-- Card Header -->
            <div class="login-card__header">
                <div class="auth-card-badge">
                    <span class="material-icons-round">lock_reset</span>
                </div>
                <h1 class="login-card__title">Lupa Kata Sandi?</h1>
                <p class="login-card__subtitle">
                    Masukkan alamat email akun admin yang terdaftar. Kami akan mengirimkan 6-digit kode OTP untuk memverifikasi akun Anda.
                </p>
            </div>

            <!-- Flash Alerts -->
            @include('admin.partials.alerts')

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('admin.password.email') }}" id="forgotPasswordForm" autocomplete="off">
                @csrf

                <div class="login-form__group">
                    <label for="email" class="login-form__label">Email Admin Terdaftar</label>
                    <div class="login-form__input-wrapper">
                        <span class="login-form__input-icon material-icons-round">mail_outline</span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="login-form__input @error('email') is-invalid @enderror"
                            placeholder="nama@kampus.ac.id"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <span class="login-form__error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="login-form__submit" id="submitBtn">
                    <span class="login-form__submit-text">Kirim Kode OTP</span>
                    <div class="login-form__spinner"></div>
                </button>

                <div class="auth-footer-action">
                    <a href="{{ route('admin.login') }}" class="auth-back-link">
                        <span class="material-icons-round">arrow_back</span>
                        <span>Kembali ke Halaman Masuk</span>
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
        const form = document.getElementById('forgotPasswordForm');
        const submitBtn = document.getElementById('submitBtn');
        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.classList.add('is-loading');
                submitBtn.disabled = true;
            });
        }
    });
</script>
@endpush
