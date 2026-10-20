<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminForgotPasswordController extends Controller
{
    /**
     * Tampilkan formulir permintaan lupa kata sandi (input email).
     */
    public function showLinkRequestForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Kirim kode OTP 6 digit ke email admin.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $admin = DB::table('Pengguna')
            ->where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if (! $admin) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email admin tidak terdaftar dalam sistem.']);
        }

        // Generate kode OTP 6 digit acak
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan OTP (hash) dan masa berlaku 15 menit ke database
        DB::table('Pengguna')
            ->where('id_user', $admin->id_user)
            ->update([
                'kode_otp'        => Hash::make($otp),
                'otp_kedaluwarsa' => now()->addMinutes(15),
            ]);

        // Simpan email ke dalam session untuk proses verifikasi
        $request->session()->put('password_reset_email', $admin->email);
        $request->session()->put('password_reset_user_id', $admin->id_user);

        // Kirim email kode OTP
        try {
            Mail::to($admin->email)->send(new ResetPasswordOtpMail($otp, $admin->nama));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirimkan email OTP reset password: ' . $e->getMessage());
        }

        // Tampilkan pesan konfirmasi (sertakan info OTP pada mode debug/log untuk kemudahan pengujian)
        $isLocalOrLog = config('app.debug') || config('mail.default') === 'log';
        $message = $isLocalOrLog
            ? "Kode OTP telah dikirim ke {$admin->email}. (Mode Pengujian: Kode OTP Anda adalah {$otp})"
            : "Kode verifikasi OTP 6 digit telah dikirim ke {$admin->email}. Silakan cek kotak masuk atau folder spam Anda.";

        return redirect()->route('admin.password.verify')
            ->with('info', $message);
    }

    /**
     * Tampilkan formulir input kode verifikasi OTP.
     */
    public function showVerifyOtpForm(Request $request)
    {
        $email = $request->session()->get('password_reset_email');

        if (! $email) {
            return redirect()->route('admin.password.request')
                ->with('error', 'Sesi verifikasi telah kedaluwarsa. Silakan masukkan email Anda kembali.');
        }

        return view('admin.auth.verify-otp', compact('email'));
    }

    /**
     * Verifikasi kode OTP yang diinputkan.
     */
    public function verifyOtp(Request $request)
    {
        $email = $request->session()->get('password_reset_email');

        if (! $email) {
            return redirect()->route('admin.password.request')
                ->with('error', 'Sesi telah kedaluwarsa. Silakan masukkan email Anda kembali.');
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.size'     => 'Kode OTP harus berjumlah 6 digit.',
        ]);

        $admin = DB::table('Pengguna')
            ->where('email', $email)
            ->where('role', 'admin')
            ->first();

        if (! $admin || ! $admin->kode_otp || ! $admin->otp_kedaluwarsa) {
            return back()->withErrors(['otp' => 'Tidak ada permintaan reset kata sandi aktif untuk akun ini.']);
        }

        if (now()->greaterThan($admin->otp_kedaluwarsa)) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan klik kirim ulang kode baru.']);
        }

        if (! Hash::check($request->otp, $admin->kode_otp)) {
            return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.']);
        }

        // Tandai verifikasi berhasil di sesi
        $request->session()->put('password_reset_verified', true);
        $request->session()->put('password_reset_verified_email', $admin->email);

        return redirect()->route('admin.password.reset')
            ->with('success', 'Kode OTP berhasil diverifikasi! Silakan tentukan kata sandi baru.');
    }

    /**
     * Kirim ulang kode OTP baru.
     */
    public function resendOtp(Request $request)
    {
        $email = $request->session()->get('password_reset_email');

        if (! $email) {
            return redirect()->route('admin.password.request')
                ->with('error', 'Sesi telah kedaluwarsa. Silakan masukkan email Anda kembali.');
        }

        $admin = DB::table('Pengguna')
            ->where('email', $email)
            ->where('role', 'admin')
            ->first();

        if (! $admin) {
            return redirect()->route('admin.password.request')
                ->with('error', 'Akun admin tidak ditemukan.');
        }

        // Generate OTP baru
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('Pengguna')
            ->where('id_user', $admin->id_user)
            ->update([
                'kode_otp'        => Hash::make($otp),
                'otp_kedaluwarsa' => now()->addMinutes(15),
            ]);

        try {
            Mail::to($admin->email)->send(new ResetPasswordOtpMail($otp, $admin->nama));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirimkan email OTP reset password: ' . $e->getMessage());
        }

        $isLocalOrLog = config('app.debug') || config('mail.default') === 'log';
        $message = $isLocalOrLog
            ? "Kode OTP baru telah dikirim. (Mode Pengujian: Kode OTP Anda adalah {$otp})"
            : "Kode OTP baru telah dikirim ke email Anda.";

        return redirect()->route('admin.password.verify')
            ->with('info', $message);
    }

    /**
     * Tampilkan formulir pengaturan kata sandi baru.
     */
    public function showResetForm(Request $request)
    {
        $isVerified = $request->session()->get('password_reset_verified');
        $email = $request->session()->get('password_reset_verified_email');

        if (! $isVerified || ! $email) {
            return redirect()->route('admin.password.request')
                ->with('error', 'Akses tidak sah. Silakan verifikasi email dan kode OTP terlebih dahulu.');
        }

        return view('admin.auth.reset-password', compact('email'));
    }

    /**
     * Simpan kata sandi baru ke database.
     */
    public function resetPassword(Request $request)
    {
        $isVerified = $request->session()->get('password_reset_verified');
        $email = $request->session()->get('password_reset_verified_email');

        if (! $isVerified || ! $email) {
            return redirect()->route('admin.password.request')
                ->with('error', 'Sesi verifikasi telah kedaluwarsa. Silakan ulangi proses reset kata sandi.');
        }

        $request->validate([
            'kata_sandi'              => 'required|string|min:6|confirmed',
        ], [
            'kata_sandi.required'     => 'Kata sandi baru wajib diisi.',
            'kata_sandi.min'          => 'Kata sandi baru minimal 6 karakter.',
            'kata_sandi.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Update password dan bersihkan kode OTP
        DB::table('Pengguna')
            ->where('email', $email)
            ->where('role', 'admin')
            ->update([
                'kata_sandi'      => Hash::make($request->kata_sandi),
                'kode_otp'        => null,
                'otp_kedaluwarsa' => null,
            ]);

        // Bersihkan seluruh data sesi reset password
        $request->session()->forget([
            'password_reset_email',
            'password_reset_user_id',
            'password_reset_verified',
            'password_reset_verified_email',
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Kata sandi Anda berhasil diperbarui! Silakan masuk dengan kata sandi baru.');
    }
}
