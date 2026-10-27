<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminProfileController extends Controller
{
    /**
     * Tampilkan halaman Profil Admin.
     */
    public function index()
    {
        $adminId = Session::get('admin_id');
        $admin = null;

        if ($adminId) {
            $admin = DB::table('Pengguna')->where('id_user', $adminId)->first();
        }

        if (!$admin) {
            $admin = DB::table('Pengguna')->where('role', 'admin')->first();
        }

        // Data admin default sesuai desain target (Theo James)
        $namaAdmin = $admin->nama ?? Session::get('admin_nama', 'Theo James');
        if (in_array($namaAdmin, ['admin@gmail.com', 'Admin CURHATIN', '']) || empty($namaAdmin)) {
            $namaAdmin = 'Theo James';
        }
        $emailAdmin  = $admin->email ?? Session::get('admin_email', 'admin@gmail.com');
        $teleponAdmin = Session::get('admin_telepon', '0812-3456-7890');
        $peranAdmin  = ucfirst($admin->role ?? 'Admin');
        $fotoAdmin   = Session::get('admin_foto', asset('images/admin/theo-james.png'));

        return view('admin.profile.index', compact(
            'namaAdmin',
            'emailAdmin',
            'teleponAdmin',
            'peranAdmin',
            'fotoAdmin'
        ));
    }

    /**
     * Perbarui informasi akun admin.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'email'   => 'required|email|max:100',
            'telepon' => 'nullable|string|max:20',
        ], [
            'nama.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $adminId = Session::get('admin_id');

        if ($adminId) {
            DB::table('Pengguna')->where('id_user', $adminId)->update([
                'nama'  => $request->nama,
                'email' => $request->email,
            ]);
        }

        Session::put('admin_nama', $request->nama);
        Session::put('admin_email', $request->email);
        if ($request->filled('telepon')) {
            Session::put('admin_telepon', $request->telepon);
        }

        return redirect()->route('admin.profile')
            ->with('success', 'Informasi akun admin berhasil diperbarui.');
    }

    /**
     * Perbarui kata sandi admin.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required|string',
            'password'              => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required'         => 'Kata sandi baru wajib diisi.',
            'password.min'              => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed'        => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $adminId = Session::get('admin_id');
        $admin = $adminId ? DB::table('Pengguna')->where('id_user', $adminId)->first() : null;

        if ($admin && !Hash::check($request->current_password, $admin->kata_sandi)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.']);
        }

        if ($adminId) {
            DB::table('Pengguna')->where('id_user', $adminId)->update([
                'kata_sandi' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.profile')
            ->with('success', 'Kata sandi admin berhasil diperbarui.');
    }

    /**
     * Ubah foto profil admin.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = 'admin_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/admin'), $fileName);

            Session::put('admin_foto', asset('images/admin/' . $fileName));
        }

        return redirect()->route('admin.profile')
            ->with('success', 'Foto profil admin berhasil diubah.');
    }

    /**
     * Hapus foto profil admin (reset ke default).
     */
    public function deletePhoto()
    {
        Session::forget('admin_foto');

        return redirect()->route('admin.profile')
            ->with('success', 'Foto profil berhasil direset ke default.');
    }
}
