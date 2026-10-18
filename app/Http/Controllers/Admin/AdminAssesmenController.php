<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminAssesmenController extends Controller
{
    /**
     * Tampilkan halaman Assesmen DASS-21.
     * Tab default: konfigurasi soal.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'konfigurasi'); // 'riwayat' | 'konfigurasi'

        // ---- KPI: Total Responden ----
        $totalResponden = DB::table('Hasil_Assesmen')->count();

        // ---- Soal DASS-21 (kategori: stres, cemas, depresi) ----
        $soalDASS = DB::table('Soal')
            ->whereIn('kategori', ['stres', 'cemas', 'depresi'])
            ->orderBy('kategori')
            ->orderBy('id_soal')
            ->paginate(6, ['*'], 'soal_page');

        $jumlahDepresi  = DB::table('Soal')->where('kategori', 'depresi')->count();
        $jumlahCemas    = DB::table('Soal')->where('kategori', 'cemas')->count();
        $jumlahStres    = DB::table('Soal')->where('kategori', 'stres')->count();
        $totalSoal      = $jumlahDepresi + $jumlahCemas + $jumlahStres;

        // ---- Riwayat Assesmen ----
        $riwayat = DB::table('Hasil_Assesmen')
            ->join('Pengguna', 'Pengguna.id_user', '=', 'Hasil_Assesmen.id_user')
            ->select(
                'Hasil_Assesmen.*',
                'Pengguna.nama',
                'Pengguna.nama_anonim',
                'Pengguna.prodi',
                'Pengguna.jurusan'
            )
            ->orderByDesc('Hasil_Assesmen.tanggal')
            ->paginate(10, ['*'], 'riwayat_page');

        return view('admin.assesmen.index', compact(
            'tab',
            'totalResponden',
            'soalDASS',
            'jumlahDepresi',
            'jumlahCemas',
            'jumlahStres',
            'totalSoal',
            'riwayat'
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
