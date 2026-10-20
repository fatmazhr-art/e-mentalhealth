@extends('admin.layouts.base')

@section('title', 'Verifikasi OTP — CURHATIN Admin')
@section('meta_description', 'Verifikasi kode OTP reset kata sandi akun admin CURHATIN')

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
                <div class="auth-step is-active">
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
                    <span class="material-icons-round">mark_email_read</span>
                </div>
                <h1 class="login-card__title">Masukkan Kode OTP</h1>
                <p class="login-card__subtitle">
                    Kode verifikasi 6 digit telah dikirimkan ke:<br>
                    <span class="auth-email-badge">
                        <span class="material-icons-round">email</span>
                        <strong>{{ $email }}</strong>
                    </span>
                </p>
            </div>

            <!-- Flash Alerts -->
            @include('admin.partials.alerts')

            <!-- Verify OTP Form -->
            <form method="POST" action="{{ route('admin.password.verify.submit') }}" id="verifyOtpForm" autocomplete="off">
                @csrf

                <input type="hidden" name="otp" id="finalOtpValue" value="">

                <div class="login-form__group">
                    <label class="login-form__label" style="text-align: center;">6 Digit Kode OTP</label>
                    <div class="otp-inputs-grid" id="otpInputsContainer">
                        <input type="text" maxlength="1" class="otp-digit-input" inputmode="numeric" pattern="[0-9]*" autofocus autocomplete="one-time-code">
                        <input type="text" maxlength="1" class="otp-digit-input" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-digit-input" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-digit-input" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-digit-input" inputmode="numeric" pattern="[0-9]*">
                        <input type="text" maxlength="1" class="otp-digit-input" inputmode="numeric" pattern="[0-9]*">
                    </div>
                    @error('otp')
                        <span class="login-form__error" style="text-align: center; margin-top: 10px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="login-form__submit" id="verifySubmitBtn">
                    <span class="login-form__submit-text">Verifikasi Kode OTP</span>
                    <div class="login-form__spinner"></div>
                </button>
            </form>

            <!-- Resend Section -->
            <div class="otp-resend-box">
                <span class="otp-resend-text">Tidak menerima kode?</span>
                <form method="POST" action="{{ route('admin.password.resend') }}" id="resendForm" style="display: inline;">
                    @csrf
                    <button type="submit" class="otp-resend-btn" id="resendOtpBtn">
                        Kirim Ulang Kode (<span id="countdown">60</span>s)
                    </button>
                </form>
            </div>

            <div class="auth-footer-action">
                <a href="{{ route('admin.password.request') }}" class="auth-back-link">
                    <span class="material-icons-round">arrow_back</span>
                    <span>Gunakan Email Lain</span>
                </a>
            </div>

        </div>
    </main>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('.otp-digit-input');
    const finalOtpInput = document.getElementById('finalOtpValue');
    const verifyForm = document.getElementById('verifyOtpForm');
    const submitBtn = document.getElementById('verifySubmitBtn');

    function updateFinalOtp() {
        let code = '';
        inputs.forEach(input => {
            code += input.value.trim();
        });
        finalOtpInput.value = code;
    }

    inputs.forEach((input, index) => {
        // Only allow numbers
        input.addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            updateFinalOtp();
        });

        // Handle Backspace
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        // Handle Paste (e.g. paste 123456)
        input.addEventListener('paste', function (e) {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim().replace(/[^0-9]/g, '');
            if (pasteData.length > 0) {
                const digits = pasteData.slice(0, 6).split('');
                digits.forEach((digit, i) => {
                    if (inputs[i]) {
                        inputs[i].value = digit;
                    }
                });
                updateFinalOtp();
                const nextIndex = Math.min(digits.length, inputs.length - 1);
                inputs[nextIndex].focus();
            }
        });
    });

    verifyForm.addEventListener('submit', function (e) {
        updateFinalOtp();
        if (finalOtpInput.value.length !== 6) {
            e.preventDefault();
            alert('Harap isi lengkap 6 digit kode OTP.');
            return false;
        }
        submitBtn.classList.add('is-loading');
        submitBtn.disabled = true;
    });

    // Countdown for Resend Button
    const resendBtn = document.getElementById('resendOtpBtn');
    const countdownSpan = document.getElementById('countdown');
    let timeLeft = 60;
    resendBtn.disabled = true;

    const timer = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(timer);
            resendBtn.disabled = false;
            resendBtn.innerHTML = 'Kirim Ulang Kode Sekarang';
            resendBtn.classList.add('is-active');
        } else {
            countdownSpan.textContent = timeLeft;
        }
    }, 1000);
});
</script>
@endpush
