<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AssessmentDummy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminAssesmenController extends Controller
{
    /**
     * Tampilkan halaman Assesmen DASS-21.
     * 
     * KEAMANAN & PRIVASI:
     * // TODO: Halaman ini menampilkan data kesehatan mental mahasiswa yang bersifat rahasia.
     * // TODO: Akses wajib dibatasi untuk admin/psikolog yang berwenang (Role-Based Access Control / Permission).
     * 
     * Tab default: 'riwayat' ("Daftar Riwayat Asesmen").
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'riwayat'); // 'riwayat' | 'konfigurasi'

        // ---- KPI: Total Responden ----
        // TODO: ganti dengan penghitungan real dari database Hasil_Assesmen
        $totalRespondenFormatted = AssessmentDummy::getTotalRespondenFormatted();

        // ---- Riwayat Assesmen Dummy Sesuai Desain Target ----
        // TODO: ganti dengan query model / database Hasil_Assesmen
        $riwayat = AssessmentDummy::getDummyHistory();

        // ---- Konfigurasi Soal (Soal DASS-21 & Soal Penyebab) ----
        // TODO: ganti dengan query model / database tabel Soal
        $soalDass = AssessmentDummy::getDummySoalDass();
        $soalPenyebab = AssessmentDummy::getDummySoalPenyebab();

        return view('admin.assesmen.index', compact(
            'tab',
            'totalRespondenFormatted',
            'riwayat',
            'soalDass',
            'soalPenyebab'
        ));
    }

    /**
     * Form tambah soal DASS-21.
     */
    public function create()
    {
        return view('admin.assesmen.create');
    }

    /**
     * Simpan soal baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:500',
            'kategori'   => 'required|in:stres,cemas,depresi',
            'bobot'      => 'required|integer|min:1|max:5',
        ]);

        $adminId = Session::get('admin_id');

        DB::table('Soal')->insert([
            'id_user'    => $adminId,
            'pertanyaan' => $request->pertanyaan,
            'kategori'   => $request->kategori,
            'bobot'      => $request->bobot,
        ]);

        return redirect()->route('admin.assesmen.index', ['tab' => 'konfigurasi'])
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    /**
     * Form edit soal.
     */
    public function edit(int $id)
    {
        $soal = DB::table('Soal')->where('id_soal', $id)->firstOrFail();
        return view('admin.assesmen.edit', compact('soal'));
    }

    /**
     * Simpan perubahan soal.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:500',
            'kategori'   => 'required|in:stres,cemas,depresi',
            'bobot'      => 'required|integer|min:1|max:5',
        ]);

        DB::table('Soal')->where('id_soal', $id)->update([
            'pertanyaan' => $request->pertanyaan,
            'kategori'   => $request->kategori,
            'bobot'      => $request->bobot,
        ]);

        return redirect()->route('admin.assesmen.index', ['tab' => 'konfigurasi'])
            ->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Hapus soal.
     */
    public function destroy(int $id)
    {
        DB::table('Soal')->where('id_soal', $id)->delete();

        return redirect()->route('admin.assesmen.index', ['tab' => 'konfigurasi'])
            ->with('success', 'Soal berhasil dihapus.');
    }
}
