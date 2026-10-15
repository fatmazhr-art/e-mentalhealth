@extends('admin.layouts.base')

@section('title', 'Login Admin — CURHATIN')
@section('meta_description', 'Halaman login admin CURHATIN - Sistem E-Mental Health Kampus')

@push('styles')
<style>
    /* ======================================
       Login Page Styles
       ====================================== */

    .login-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: linear-gradient(160deg, #C08A7A 0%, #B07878 25%, #987070 50%, #806868 75%, #685858 100%);
        position: relative;
        overflow: hidden;
    }

    /* Subtle animated background particles */
    .login-page::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background:
            radial-gradient(circle at 20% 50%, rgba(139, 30, 30, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 248, 240, 0.06) 0%, transparent 50%),
            radial-gradient(circle at 60% 80%, rgba(139, 30, 30, 0.05) 0%, transparent 50%);
        animation: bgFloat 20s ease-in-out infinite;
    }

    @keyframes bgFloat {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(2%, -1%) rotate(1deg); }
        66% { transform: translate(-1%, 2%) rotate(-1deg); }
    }

    /* ---- Top Navbar ---- */
    .login-navbar {
        position: relative;
        z-index: 10;
        display: flex;
        align-items: center;
        padding: 16px 32px;
        background: var(--maroon-800);
        box-shadow: 0 2px 12px rgba(59, 10, 10, 0.3);
    }

    .login-navbar__logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .login-navbar__icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--maroon-300), var(--maroon-500));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(139, 30, 30, 0.4);
    }

    .login-navbar__icon .material-icons-round {
        font-size: 22px;
        color: white;
    }

    .login-navbar__brand {
        font-size: 20px;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    /* ---- Login Container ---- */
    .login-container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        position: relative;
        z-index: 5;
    }

    .login-card {
        width: 100%;
        max-width: 540px;
        background: var(--cream-100);
        border-radius: var(--radius-xl);
        padding: 48px 44px;
        box-shadow:
            0 25px 50px -12px rgba(59, 10, 10, 0.25),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        animation: cardSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }

    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--maroon-500), var(--maroon-300), var(--maroon-500));
    }

    @keyframes cardSlideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.97);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* ---- Card Header ---- */
    .login-card__header {
        text-align: center;
        margin-bottom: 36px;
    }

    .login-card__title {
        font-size: 32px;
        font-weight: 800;
        color: var(--maroon-800);
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .login-card__subtitle {
        font-size: 14px;
        font-weight: 500;
        color: var(--neutral-600);
        line-height: 1.5;
    }

    /* ---- Alert Messages ---- */
    .login-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: var(--radius-sm);
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 20px;
        animation: alertFadeIn 0.3s ease;
    }

    @keyframes alertFadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .login-alert--error {
        background: #FEF2F2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    .login-alert--success {
        background: #F0FDF4;
        color: #166534;
        border: 1px solid #BBF7D0;
    }

    .login-alert .material-icons-round {
        font-size: 18px;
        flex-shrink: 0;
    }

    /* ---- Form ---- */
    .login-form__group {
        margin-bottom: 20px;
    }

    .login-form__label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: var(--maroon-800);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
    }

    .login-form__input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .login-form__input-icon {
        position: absolute;
        left: 14px;
        color: var(--neutral-400);
        font-size: 20px;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .login-form__input {
        width: 100%;
        padding: 14px 14px 14px 46px;
        border: 2px solid var(--neutral-200);
        border-radius: var(--radius-sm);
        font-family: var(--font-family);
        font-size: 15px;
        font-weight: 400;
        color: var(--neutral-800);
        background: #FFFFFF;
        transition: all 0.25s ease;
        outline: none;
    }

    .login-form__input::placeholder {
        color: var(--neutral-400);
    }

    .login-form__input:focus {
        border-color: var(--maroon-400);
        box-shadow: 0 0 0 3px rgba(139, 30, 30, 0.1);
    }

    .login-form__input:focus ~ .login-form__input-icon,
    .login-form__input:focus + .login-form__input-icon {
        color: var(--maroon-500);
    }

    .login-form__input-wrapper:focus-within .login-form__input-icon {
        color: var(--maroon-500);
    }

    .login-form__input.is-invalid {
        border-color: var(--danger);
    }

    .login-form__error {
        display: block;
        font-size: 12px;
        color: var(--danger);
        margin-top: 6px;
        font-weight: 500;
    }

    /* Toggle password visibility */
    .login-form__toggle-password {
        position: absolute;
        right: 14px;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--neutral-400);
        padding: 4px;
        display: flex;
        align-items: center;
        transition: color 0.2s ease;
    }

    .login-form__toggle-password:hover {
        color: var(--maroon-500);
    }

    .login-form__toggle-password .material-icons-round {
        font-size: 20px;
    }

    /* ---- Remember + Forgot ---- */
    .login-form__options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .login-form__remember {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .login-form__remember input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--maroon-500);
        cursor: pointer;
    }

    .login-form__remember-text {
        font-size: 13px;
        color: var(--neutral-600);
        font-weight: 500;
        user-select: none;
    }

    .login-form__forgot {
        font-size: 13px;
        font-weight: 600;
        color: var(--maroon-600);
        transition: color 0.2s ease;
    }

    .login-form__forgot:hover {
        color: var(--maroon-400);
        text-decoration: underline;
    }

    /* ---- Submit Button ---- */
    .login-form__submit {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, var(--maroon-800), var(--maroon-600));
        color: #FFFFFF;
        border: none;
        border-radius: var(--radius-sm);
        font-family: var(--font-family);
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .login-form__submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        transition: left 0.5s ease;
    }

    .login-form__submit:hover {
        background: linear-gradient(135deg, var(--maroon-700), var(--maroon-500));
        box-shadow: 0 4px 16px rgba(139, 30, 30, 0.4);
        transform: translateY(-1px);
    }

    .login-form__submit:hover::before {
        left: 100%;
    }

    .login-form__submit:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(139, 30, 30, 0.3);
    }

    .login-form__submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* ---- Loading spinner ---- */
    .login-form__spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #FFFFFF;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .login-form__submit.is-loading .login-form__submit-text {
        display: none;
    }

    .login-form__submit.is-loading .login-form__spinner {
        display: block;
    }

    /* ---- Responsive ---- */
    @media (max-width: 600px) {
        .login-navbar {
            padding: 12px 20px;
        }

        .login-navbar__brand {
            font-size: 17px;
        }

        .login-card {
            padding: 36px 24px;
            border-radius: var(--radius-lg);
        }

        .login-card__title {
            font-size: 26px;
        }

        .login-form__options {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('body')
<div class="login-page">

    {{-- Navbar --}}
    <nav class="login-navbar" role="navigation" aria-label="Admin Navigation">
        <div class="login-navbar__logo">
            <div class="login-navbar__icon">
                <span class="material-icons-round">favorite</span>
            </div>
            <span class="login-navbar__brand">CURHATIN</span>
        </div>
    </nav>

    {{-- Login Card --}}
    <main class="login-container">
        <div class="login-card">
            <div class="login-card__header">
                <h1 class="login-card__title">Selamat Datang</h1>
                <p class="login-card__subtitle">
                    Silakan masuk dengan akun SSO Kampus atau kredensial terdaftar anda.
                </p>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="login-alert login-alert--success" role="alert">
                    <span class="material-icons-round">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="login-alert login-alert--error" role="alert">
                    <span class="material-icons-round">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->has('login'))
                <div class="login-alert login-alert--error" role="alert">
                    <span class="material-icons-round">error</span>
                    <span>{{ $errors->first('login') }}</span>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="login-form__group">
                    <label for="email" class="login-form__label">EMAIL</label>
                    <div class="login-form__input-wrapper">
                        <span class="login-form__input-icon material-icons-round">person_outline</span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="login-form__input @error('email') is-invalid @enderror"
                            placeholder="contoh@email.com"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                        >
                    </div>
                    @error('email')
                        <span class="login-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Kata Sandi --}}
                <div class="login-form__group">
                    <label for="kata_sandi" class="login-form__label">Kata Sandi</label>
                    <div class="login-form__input-wrapper">
                        <span class="login-form__input-icon material-icons-round">vpn_key</span>
                        <input
                            type="password"
                            id="kata_sandi"
                            name="kata_sandi"
                            class="login-form__input @error('kata_sandi') is-invalid @enderror"
                            placeholder="Masukkan kata sandi"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="login-form__toggle-password" id="togglePassword" aria-label="Toggle password visibility">
                            <span class="material-icons-round">visibility_off</span>
                        </button>
                    </div>
                    @error('kata_sandi')
                        <span class="login-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Options --}}
                <div class="login-form__options">
                    <label class="login-form__remember">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="login-form__remember-text">Ingat Saya diperangkat ini</span>
                    </label>
                    <a href="#" class="login-form__forgot">Lupa Kata Sandi?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="login-form__submit" id="loginSubmitBtn">
                    <span class="login-form__submit-text">Masuk</span>
                    <div class="login-form__spinner"></div>
                </button>
            </form>
        </div>
    </main>

</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('kata_sandi');
        const icon = this.querySelector('.material-icons-round');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.textContent = 'visibility';
        } else {
            passwordInput.type = 'password';
            icon.textContent = 'visibility_off';
        }
    });

    // Loading state on submit
    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn = document.getElementById('loginSubmitBtn');
        btn.classList.add('is-loading');
        btn.disabled = true;
    });
</script>
@endpush
