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
    public function index(Request $request)
    {
        $adminNama  = Session::get('admin_nama', 'Admin');
        $adminEmail = Session::get('admin_email', '');
        $view       = $request->query('view', 'overview'); // 'overview' (default sesuai screenshot) atau 'detail'
        $jurusan    = $request->query('jurusan', 'Teknik Informatika');

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
            ? 'Rata - rata: ' . ucfirst($rataKategori->kategori_hasil) . ' Sedang'
            : 'Rata - rata: Stress Sedang';

        // --- Distribusi DASS-21 per skala ---
        $distribusi = $this->hitungDistribusiDASS();

        // --- Tabel jurusan dengan kasus berat ---
        $tabelJurusan = $this->hitungKasusPerJurusan();

        // --- Data Kasus Berat Antar Jurusan (Sesuai Desain) ---
        $kasusAntarJurusan = [
            ['jurusan' => 'Teknik Informatika', 'percent' => 80, 'class' => 'ti', 'color' => '#700000'],
            ['jurusan' => 'Teknik Mesin',       'percent' => 60, 'class' => 'tm', 'color' => '#A33A48'],
            ['jurusan' => 'Teknik Elektro',     'percent' => 70, 'class' => 'te', 'color' => '#700000'],
            ['jurusan' => 'Manajemen Bisnis',   'percent' => 35, 'class' => 'mb', 'color' => '#D47A88'],
        ];

        // --- Data Stat Detail per Jurusan ---
        $detailJurusanStats = [
            'Teknik Informatika' => [
                'kasus'        => '142',
                'persen_label' => '12,5% dari 1.136 mahasiswa',
                'tren'         => '+ 28%',
                'tren_label'   => 'Dipicu Tugas Akhir',
            ],
            'Manajemen Bisnis' => [
                'kasus'        => '98',
                'persen_label' => '6,5% dari 1.508 mahasiswa',
                'tren'         => '+ 14%',
                'tren_label'   => 'Dipicu Stase',
            ],
            'Teknik Mesin' => [
                'kasus'        => '84',
                'persen_label' => '5,5% dari 1.527 mahasiswa',
                'tren'         => 'Stabil',
                'tren_label'   => 'Kondisi Terkendali',
            ],
            'Teknik Elektro' => [
                'kasus'        => '62',
                'persen_label' => '4,5% dari 1.378 mahasiswa',
                'tren'         => 'Stabil',
                'tren_label'   => 'Kondisi Terkendali',
            ],
        ];
        $currentDetailStat = $detailJurusanStats[$jurusan] ?? $detailJurusanStats['Teknik Informatika'];

        return view('admin.dashboard.index', compact(
            'adminNama',
            'adminEmail',
            'view',
            'jurusan',
            'totalPengguna',
            'persenAnonim',
            'totalSkrining',
            'labelRataRata',
            'distribusi',
            'tabelJurusan',
            'kasusAntarJurusan',
            'currentDetailStat'
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
        $jurusanList = [
            'Teknik Informatika' => [
                'default_kasus'  => 142,
                'default_persen' => 12.5,
                'default_tren'   => '+28% Tugas Akhir',
                'default_status' => 'Perhatian Khusus',
                'default_class'  => 'perhatian',
            ],
            'Manajemen Bisnis' => [
                'default_kasus'  => 98,
                'default_persen' => 6.5,
                'default_tren'   => '+14% (Stase)',
                'default_status' => 'Waspada',
                'default_class'  => 'waspada',
            ],
            'Teknik Mesin' => [
                'default_kasus'  => 84,
                'default_persen' => 5.5,
                'default_tren'   => 'Stabil',
                'default_status' => 'Terkendali',
                'default_class'  => 'terkendali',
            ],
            'Teknik Elektro' => [
                'default_kasus'  => 62,
                'default_persen' => 4.5,
                'default_tren'   => 'Stabil',
                'default_status' => 'Terkendali',
                'default_class'  => 'terkendali',
            ],
        ];

        $rows = DB::table('Hasil_Assesmen')
            ->join('Pengguna', 'Pengguna.id_user', '=', 'Hasil_Assesmen.id_user')
            ->whereIn('Pengguna.jurusan', array_keys($jurusanList))
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
            ->get()
            ->keyBy('jurusan');

        $result = [];
        foreach ($jurusanList as $namaJurusan => $def) {
            if (isset($rows[$namaJurusan]) && $rows[$namaJurusan]->total_skrining > 0) {
                $row = $rows[$namaJurusan];
                $persenBerat = round(($row->kasus_berat / $row->total_skrining) * 100, 1);

                if ($row->penyebab_ta > $row->penyebab_magang && $row->penyebab_ta > 0) {
                    $tren = '+' . round(($row->penyebab_ta / max($row->total_skrining, 1)) * 100) . '% Tugas Akhir';
                } elseif ($row->penyebab_magang > 0) {
                    $tren = '+' . round(($row->penyebab_magang / max($row->total_skrining, 1)) * 100) . '% (Stase)';
                } else {
                    $tren = 'Stabil';
                }

                if ($persenBerat >= 10) {
                    $status = 'Perhatian Khusus';
                    $statusClass = 'perhatian';
                } elseif ($persenBerat >= 5) {
                    $status = 'Waspada';
                    $statusClass = 'waspada';
                } else {
                    $status = 'Terkendali';
                    $statusClass = 'terkendali';
                }

                $result[] = (object) [
                    'jurusan'      => $namaJurusan,
                    'kasus_berat'  => $row->kasus_berat,
                    'persen_berat' => $persenBerat,
                    'tren'         => $tren,
                    'status'       => $status,
                    'status_class' => $statusClass,
                ];
            } else {
                $result[] = (object) [
                    'jurusan'      => $namaJurusan,
                    'kasus_berat'  => $def['default_kasus'],
                    'persen_berat' => $def['default_persen'],
                    'tren'         => $def['default_tren'],
                    'status'       => $def['default_status'],
                    'status_class' => $def['default_class'],
                ];
            }
        }

        return $result;
    }

    /**
     * Tampilkan halaman detail per kategori DASS-21 (Depresi, Kecemasan, Stress).
     */
    public function detail(Request $request, $kategori)
    {
        $kategoriSlug = strtolower(trim($kategori));
        $validKategori = [
            'depresi'   => 'Depresi',
            'kecemasan' => 'Kecemasan',
            'stress'    => 'Stress',
        ];

        if (! array_key_exists($kategoriSlug, $validKategori)) {
            return redirect()->route('admin.dashboard.detail', 'depresi');
        }

        $kategoriTitle = $validKategori[$kategoriSlug];
        $periode       = $request->query('periode', '1 bulan');

        $data = [
            'kategori'      => $kategoriTitle,
            'kategoriSlug'  => $kategoriSlug,
            'periode'       => $periode,
            'distribusi'    => [
                ['label' => 'Normal',       'rentang' => '0 - 9',       'persen' => 55, 'warna' => '#9ACD00'],
                ['label' => 'Ringan',       'rentang' => '10 - 13',     'persen' => 20, 'warna' => '#1A3FF0'],
                ['label' => 'Sedang',       'rentang' => '14 - 20',     'persen' => 10, 'warna' => '#EBA100'],
                ['label' => 'Berat',        'rentang' => '21 - 27',     'persen' => 15, 'warna' => '#E50000'],
                ['label' => 'Sangat Berat', 'rentang' => '28 ke atas',  'persen' => 5,  'warna' => '#700000'],
            ],
            'tren'          => [
                ['label' => 'Minggu 1', 'persen' => 16],
                ['label' => 'Minggu 2', 'persen' => 20],
                ['label' => 'Minggu 3', 'persen' => 24],
                ['label' => 'Minggu 4', 'persen' => 30],
            ],
            'penyebab'      => [
                ['label' => 'Tugas Akhir', 'persen' => 80],
                ['label' => 'Magang',      'persen' => 50],
                ['label' => 'UAS',         'persen' => 67],
            ],
        ];

        return view('admin.dashboard.detail', $data);
    }
}

