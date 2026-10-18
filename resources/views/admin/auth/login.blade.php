{{--
    ======================================
    CURHATIN Admin — Login Page
    ======================================
    Halaman login admin: /admin/login
    
    CSS  : public/css/admin/login.css
    JS   : public/js/admin/login.js
    
    KOMPONEN YANG DIPAKAI:
    - @include('admin.partials.alerts')  → Flash messages
    ======================================
--}}
@extends('admin.layouts.base')

@section('title', 'Login Admin — CURHATIN')
@section('meta_description', 'Halaman login admin CURHATIN - Sistem E-Mental Health Kampus')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">
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
            @include('admin.partials.alerts')

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
                            placeholder="admin@gmail.com"
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
    <script src="{{ asset('js/admin/login.js') }}"></script>
@endpush
