<?php

namespace App\Support;

/**
 * Class AssessmentDummy
 * 
 * Modul penyedia data dummy dan kalkulasi skala DASS-21.
 * 
 * KEAMANAN & PRIVASI:
 * // TODO: Halaman dan data ini menampilkan data kesehatan mental mahasiswa yang bersifat rahasia.
 * // TODO: Akses wajib dibatasi hanya untuk admin atau psikolog resmi kampus yang berwenang (Role-Based Access Control / Permission).
 */
class AssessmentDummy
{
    /**
     * Batas Rentang Skor DASS-21 (Skor sudah dikali 2 sesuai instrumen baku DASS-21):
     * - Depresi:
     *     Normal: 0 - 9
     *     Ringan: 10 - 13
     *     Sedang: 14 - 20
     *     Berat: 21 - 27
     *     Sangat berat: 28+
     * - Kecemasan:
     *     Normal: 0 - 7
     *     Ringan: 8 - 9
     *     Sedang: 10 - 14
     *     Berat: 15 - 19
     *     Sangat berat: 20+
     * - Stres:
     *     Normal: 0 - 14
     *     Ringan: 15 - 18
     *     Sedang: 19 - 25
     *     Berat: 26 - 33
     *     Sangat berat: 34+
     */
    public const SCALE_RANGES = [
        'depresi' => [
            'normal'       => [0, 9],
            'ringan'       => [10, 13],
            'sedang'       => [14, 20],
            'berat'        => [21, 27],
            'sangat_berat' => [28, PHP_INT_MAX],
        ],
        'kecemasan' => [
            'normal'       => [0, 7],
            'ringan'       => [8, 9],
            'sedang'       => [10, 14],
            'berat'        => [15, 19],
            'sangat_berat' => [20, PHP_INT_MAX],
        ],
        'stres' => [
            'normal'       => [0, 14],
            'ringan'       => [15, 18],
            'sedang'       => [19, 25],
            'berat'        => [26, 33],
            'sangat_berat' => [34, PHP_INT_MAX],
        ],
    ];

    /**
     * Konfigurasi label dan warna badge sesuai panduan desain TARGET:
     * - Normal: hijau #9ACD00 (teks gelap #1A1A1A)
     * - Ringan: biru #1744F5 (teks putih #FFFFFF)
     * - Sedang: kuning-oranye #F0A500 (teks putih #FFFFFF)
     * - Berat: merah #E00000 (teks putih #FFFFFF)
     * - Sangat berat: maroon gelap #6E0000 (teks putih #FFFFFF)
     */
    public const CATEGORY_BADGES = [
        'normal' => [
            'label'    => 'normal',
            'bg'       => '#9ACD00',
            'text'     => '#1A1A1A',
        ],
        'ringan' => [
            'label'    => 'ringan',
            'bg'       => '#1744F5',
            'text'     => '#FFFFFF',
        ],
        'sedang' => [
            'label'    => 'sedang',
            'bg'       => '#F0A500',
            'text'     => '#FFFFFF',
        ],
        'berat' => [
            'label'    => 'berat',
            'bg'       => '#E00000',
            'text'     => '#FFFFFF',
        ],
        'sangat_berat' => [
            'label'    => 'sangat berat',
            'bg'       => '#6E0000',
            'text'     => '#FFFFFF',
        ],
    ];

    /**
     * Hitung kategori skala DASS-21 berdasarkan skor.
     * 
     * @param string $scale ('depresi' | 'kecemasan' | 'stres')
     * @param int $score
     * @return string ('normal' | 'ringan' | 'sedang' | 'berat' | 'sangat_berat')
     */
    public static function getCategory(string $scale, int $score): string
    {
        $scale = strtolower($scale);
        if (!isset(self::SCALE_RANGES[$scale])) {
            return 'normal';
        }

        foreach (self::SCALE_RANGES[$scale] as $category => [$min, $max]) {
            if ($score >= $min && $score <= $max) {
                return $category;
            }
        }

        return 'normal';
    }

    /**
     * Ambil data badge (label, background, warna teks) berdasarkan skor & skala.
     * 
     * @param string $scale
     * @param int $score
     * @return array
     */
    public static function getBadgeInfo(string $scale, int $score): array
    {
        $cat = self::getCategory($scale, $score);
        return self::CATEGORY_BADGES[$cat] ?? self::CATEGORY_BADGES['normal'];
    }

    /**
     * Aturan status rekomendasi asesmen:
     * Aturan sementara: "Konseling segera" jika ada skor berkategori Berat atau Sangat berat;
     * selain itu "Pantau".
     * 
     * // TODO: Konfirmasi aturan penentuan status ini dengan tim psikolog profesional.
     * 
     * @param int $depresi
     * @param int $kecemasan
     * @param int $stres
     * @return string
     */
    public static function determineStatus(int $depresi, int $kecemasan, int $stres): string
    {
        $catDepresi   = self::getCategory('depresi', $depresi);
        $catKecemasan = self::getCategory('kecemasan', $kecemasan);
        $catStres     = self::getCategory('stres', $stres);

        $isSevere = in_array($catDepresi, ['berat', 'sangat_berat'])
            || in_array($catKecemasan, ['berat', 'sangat_berat'])
            || in_array($catStres, ['berat', 'sangat_berat']);

        return $isSevere ? 'Konseling segera' : 'Pantau';
    }

    /**
     * Ambil 4 baris data riwayat dummy sesuai desain target.
     * Dilengkapi variasi skor agar seluruh kategori badge (normal, ringan, sedang, berat, sangat berat)
     * dapat terlihat dan teruji dengan sempurna.
     * 
     * // TODO: ganti dengan data asli dari database/API Hasil_Assesmen
     * 
     * @return array
     */
    public static function getDummyHistory(): array
    {
        $rawData = [
            [
                'id'        => '001',
                'nama'      => 'Theo james',
                'prodi'     => 'TRPL',
                'depresi'   => 28, // Sangat berat (28+)
                'kecemasan' => 19, // Berat (15-19)
                'stres'     => 22, // Sedang (19-25)
            ],
            [
                'id'        => '002',
                'nama'      => 'Anonim 345',
                'prodi'     => '-',
                'depresi'   => 24, // Berat (21-27)
                'kecemasan' => 19, // Berat (15-19)
                'stres'     => 22, // Sedang (19-25)
            ],
            [
                'id'        => '003',
                'nama'      => 'Anonim 398',
                'prodi'     => '-',
                'depresi'   => 12, // Ringan (10-13)
                'kecemasan' => 8,  // Ringan (8-9)
                'stres'     => 10, // Normal (0-14)
            ],
            [
                'id'        => '004',
                'nama'      => 'hosieni',
                'prodi'     => 'TRPL',
                'depresi'   => 6,  // Normal (0-9)
                'kecemasan' => 12, // Sedang (10-14)
                'stres'     => 35, // Sangat berat (34+)
            ],
        ];

        return array_map(function ($item) {
            $item['badge_depresi']   = self::getBadgeInfo('depresi', $item['depresi']);
            $item['badge_kecemasan'] = self::getBadgeInfo('kecemasan', $item['kecemasan']);
            $item['badge_stres']     = self::getBadgeInfo('stres', $item['stres']);
            $item['status']          = self::determineStatus($item['depresi'], $item['kecemasan'], $item['stres']);
            return $item;
        }, $rawData);
    }

    /**
     * Total responden yang diformat.
     * Menggunakan format ribuan titik (contoh 1.136).
     * 
     * // TODO: ganti dengan count real dari tabel Hasil_Assesmen
     * 
     * @return string
     */
    public static function getTotalRespondenFormatted(): string
    {
        // Contoh angka ribuan sesuai spesifikasi instruksi:
        $count = 1136;
        return number_format($count, 0, ',', '.');
    }

    /**
     * Data dummy Soal DASS-21 untuk tab Konfigurasi Soal sesuai desain target.
     * 
     * // TODO: ganti dengan query model / database tabel Soal
     * 
     * @return array
     */
    public static function getDummySoalDass(): array
    {
        return [
            [
                'no'         => 1,
                'pertanyaan' => 'Saya merasa sulit untuk bersantai',
                'skala'      => 'Stress',
                'skala_bg'   => '#E00000',
                'status'     => 'Aktif',
            ],
            [
                'no'         => 2,
                'pertanyaan' => 'Saya merasa mulut saya kering',
                'skala'      => 'Kecemasan',
                'skala_bg'   => '#5A6268',
                'status'     => 'Aktif',
            ],
            [
                'no'         => 3,
                'pertanyaan' => 'Saya tidak dapat merasakan perasaan positif sama sekali',
                'skala'      => 'Depresi',
                'skala_bg'   => '#1A3A6B',
                'status'     => 'Aktif',
            ],
            [
                'no'         => 4,
                'pertanyaan' => 'Saya mengalami kesulitan bernapas',
                'skala'      => 'Kecemasan',
                'skala_bg'   => '#5A6268',
                'status'     => 'Aktif',
            ],
            [
                'no'         => 5,
                'pertanyaan' => 'Saya merasa sulit untuk memulai melakukan sesuatu',
                'skala'      => 'Depresi',
                'skala_bg'   => '#1A3A6B',
                'status'     => 'Aktif',
            ],
            [
                'no'         => 6,
                'pertanyaan' => 'Saya cenderung bereaksi berlebihan terhadap situasi',
                'skala'      => 'Stress',
                'skala_bg'   => '#E00000',
                'status'     => 'Aktif',
            ],
        ];
    }

    /**
     * Data dummy Soal Penyebab untuk tab Konfigurasi Soal sesuai desain target.
     * 
     * // TODO: ganti dengan query model / database tabel Soal Penyebab
     * 
     * @return array
     */
    public static function getDummySoalPenyebab(): array
    {
        return [
            [
                'no'           => 1,
                'pertanyaan'   => 'Apa yang paling memengaruhi kondisimu saat ini?',
                'opsi_jawaban' => ['Tugas Akhir', 'Magang', 'Lainnya'],
                'tampil_untuk' => 'semua skala',
                'status'       => 'Aktif',
            ],
            [
                'no'           => 1,
                'pertanyaan'   => 'Seberapa sering kamu merasa kewalahan oleh tugas akhir?',
                'opsi_jawaban' => ['Jarang', 'Kadang sering'],
                'tampil_untuk' => 'stress',
                'status'       => 'Aktif',
            ],
            [
                'no'           => 1,
                'pertanyaan'   => 'Ceritakan singkat hal lain yang membebani pikiranmu',
                'opsi_jawaban' => ['Tugas Akhir', 'Magang', 'Lainnya'],
                'tampil_untuk' => 'semua skala',
                'status'       => 'Aktif',
            ],
        ];
    }
}
