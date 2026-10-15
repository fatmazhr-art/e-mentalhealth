<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin.
     */
    public function index()
    {
        $adminNama  = Session::get('admin_nama', 'Admin');
        $adminEmail = Session::get('admin_email', '');

        // --- Statistik Utama ---
        $totalPengguna = DB::table('Pengguna')->where('role', 'user')->count();
        $totalAnonim   = DB::table('Pengguna')->where('role', 'user')
            ->whereNotNull('nama_anonim')->count();
        $persenAnonim  = $totalPengguna > 0
            ? round(($totalAnonim / $totalPengguna) * 100)
            : 0;

        $totalSkrining = DB::table('Hasil_Assesmen')->count();

        // Rata-rata kategori hasil terbanyak
        $rataKategori = DB::table('Hasil_Assesmen')
            ->select('kategori_hasil', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kategori_hasil')
            ->orderByDesc('jumlah')
            ->first();
        $labelRataRata = $rataKategori
            ? 'Rata-rata: ' . ucfirst($rataKategori->kategori_hasil) . ' Sedang'
            : 'Belum ada data';

        // --- Distribusi DASS-21 per skala ---
        $distribusi = $this->hitungDistribusiDASS();

        // --- Tabel jurusan dengan kasus berat ---
        $tabelJurusan = $this->hitungKasusPerJurusan();

        return view('admin.dashboard.index', compact(
            'adminNama',
            'adminEmail',
            'totalPengguna',
            'persenAnonim',
            'totalSkrining',
            'labelRataRata',
            'distribusi',
            'tabelJurusan'
        ));
    }

    /**
     * Hitung distribusi tingkat keparahan DASS-21 untuk Depresi, Kecemasan, Stress.
     *
     * Cut-off skor DASS-21 (x2 dari versi 21-item):
     * Depresi:   Normal 0-9, Ringan 10-13, Sedang 14-20, Berat 21-27, Sangat Berat 28+
     * Kecemasan: Normal 0-7, Ringan 8-9,   Sedang 10-14, Berat 15-19, Sangat Berat 20+
     * Stress:    Normal 0-14,Ringan 15-18,  Sedang 19-25, Berat 26-33, Sangat Berat 34+
     */
    private function hitungDistribusiDASS(): array
    {
        $total = DB::table('Hasil_Assesmen')->count();

        if ($total === 0) {
            $kosong = [
                'normal' => 0, 'ringan' => 0, 'sedang' => 0,
                'berat' => 0, 'sangat_berat' => 0,
            ];
            return [
                'depresi'   => $kosong,
                'kecemasan' => $kosong,
                'stress'    => $kosong,
            ];
        }

        // --- Depresi ---
        $depresi = [
            'normal'       => $this->persenKolom('skor_depresi', 0, 9, $total),
            'ringan'       => $this->persenKolom('skor_depresi', 10, 13, $total),
            'sedang'       => $this->persenKolom('skor_depresi', 14, 20, $total),
            'berat'        => $this->persenKolom('skor_depresi', 21, 27, $total),
            'sangat_berat' => $this->persenKolom('skor_depresi', 28, 99, $total),
        ];

        // --- Kecemasan ---
        $kecemasan = [
            'normal'       => $this->persenKolom('skor_cemas', 0, 7, $total),
            'ringan'       => $this->persenKolom('skor_cemas', 8, 9, $total),
            'sedang'       => $this->persenKolom('skor_cemas', 10, 14, $total),
            'berat'        => $this->persenKolom('skor_cemas', 15, 19, $total),
            'sangat_berat' => $this->persenKolom('skor_cemas', 20, 99, $total),
        ];

        // --- Stress ---
        $stress = [
            'normal'       => $this->persenKolom('skor_stress', 0, 14, $total),
            'ringan'       => $this->persenKolom('skor_stress', 15, 18, $total),
            'sedang'       => $this->persenKolom('skor_stress', 19, 25, $total),
            'berat'        => $this->persenKolom('skor_stress', 26, 33, $total),
            'sangat_berat' => $this->persenKolom('skor_stress', 34, 99, $total),
        ];

        return compact('depresi', 'kecemasan', 'stress');
    }

    /**
     * Hitung persentase skor di rentang tertentu.
     */
    private function persenKolom(string $kolom, int $min, int $max, int $total): int
    {
        $count = DB::table('Hasil_Assesmen')
            ->whereBetween($kolom, [$min, $max])
            ->count();

        return (int) round(($count / $total) * 100);
    }

    /**
     * Hitung kasus berat per jurusan, beserta tren penyebab dan status.
     */
    private function hitungKasusPerJurusan(): array
    {
        $rows = DB::table('Hasil_Assesmen')
            ->join('Pengguna', 'Pengguna.id_user', '=', 'Hasil_Assesmen.id_user')
            ->whereNotNull('Pengguna.jurusan')
            ->select(
                'Pengguna.jurusan',
                DB::raw('COUNT(*) as total_skrining'),
                DB::raw("SUM(CASE
                    WHEN skor_depresi >= 21 OR skor_cemas >= 15 OR skor_stress >= 26
                    THEN 1 ELSE 0 END) as kasus_berat"),
                DB::raw("SUM(CASE WHEN kategori_penyebab = 'tugas_akhir' THEN 1 ELSE 0 END) as penyebab_ta"),
                DB::raw("SUM(CASE WHEN kategori_penyebab = 'magang' THEN 1 ELSE 0 END) as penyebab_magang")
            )
            ->groupBy('Pengguna.jurusan')
            ->orderByDesc('kasus_berat')
            ->limit(5)
            ->get();

        $result = [];
        foreach ($rows as $row) {
            $persenBerat = $row->total_skrining > 0
                ? round(($row->kasus_berat / $row->total_skrining) * 100, 1)
                : 0;

            // Tentukan tren berdasarkan penyebab dominan
            if ($row->penyebab_ta > $row->penyebab_magang && $row->penyebab_ta > 0) {
                $tren = '+' . round(($row->penyebab_ta / max($row->total_skrining, 1)) * 100) . '% Tugas Akhir';
            } elseif ($row->penyebab_magang > 0) {
                $tren = '+' . round(($row->penyebab_magang / max($row->total_skrining, 1)) * 100) . '% Magang';
            } else {
                $tren = 'Stabil';
            }

            // Tentukan status berdasarkan persentase kasus berat
            if ($persenBerat >= 10) {
                $status = 'Perhatian Khusus';
                $statusClass = 'danger';
            } elseif ($persenBerat >= 5) {
                $status = 'Waspada';
                $statusClass = 'warning';
            } else {
                $status = 'Terkendali';
                $statusClass = 'success';
            }

            $result[] = (object) [
                'jurusan'      => $row->jurusan,
                'kasus_berat'  => $row->kasus_berat,
                'persen_berat' => $persenBerat,
                'tren'         => $tren,
                'status'       => $status,
                'status_class' => $statusClass,
            ];
        }

        return $result;
    }
}
