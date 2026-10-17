<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan halaman login admin.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Proses login admin.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'      => 'required|email',
            'kata_sandi' => 'required|string|min:6',
        ], [
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'kata_sandi.required' => 'Kata sandi wajib diisi.',
            'kata_sandi.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        // Cari pengguna berdasarkan email dan role admin
        $admin = DB::table('Pengguna')
            ->where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if (! $admin || ! Hash::check($request->kata_sandi, $admin->kata_sandi)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['login' => 'Email atau kata sandi salah, atau Anda bukan admin.']);
        }

        // Simpan sesi admin
        Session::put('admin_logged_in', true);
        Session::put('admin_id', $admin->id_user);
        Session::put('admin_nama', $admin->nama);
        Session::put('admin_email', $admin->email);

        // Regenerate session untuk keamanan
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Selamat datang, ' . $admin->nama . '!');
    }

    /**
     * Proses logout admin.
     */
    public function logout(Request $request)
    {
        Session::forget(['admin_logged_in', 'admin_id', 'admin_nama', 'admin_email']);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
