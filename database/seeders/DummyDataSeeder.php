<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    /**
     * Seed seluruh tabel dengan data dummy yang realistis untuk e-mental health kampus.
     */
    public function run(): void
    {
        // =====================================================================
        // 1. PENGGUNA — Admin, Konselor, dan Mahasiswa
        // =====================================================================
        $now = Carbon::now();

        $penggunaData = [
            // Admin
            ['nama' => 'Admin CURHATIN',      'nama_anonim' => null,             'role' => 'admin',    'prodi' => 'Pusat Layanan Konseling', 'jurusan' => 'Kesehatan Mental Kampus', 'nim' => null,        'email' => 'admin@gmail.com',             'kata_sandi' => Hash::make('admin123')],

            // Konselor
            ['nama' => 'Dr. Rina Kartika',    'nama_anonim' => null,             'role' => 'konselor', 'prodi' => 'Psikologi Klinis',        'jurusan' => 'Psikologi',              'nim' => null,        'email' => 'rina.konselor@curhatin.com',  'kata_sandi' => Hash::make('konselor123')],
            ['nama' => 'Hendra Wijaya, M.Psi','nama_anonim' => null,             'role' => 'konselor', 'prodi' => 'Psikologi Konseling',     'jurusan' => 'Psikologi',              'nim' => null,        'email' => 'hendra.konselor@curhatin.com','kata_sandi' => Hash::make('konselor123')],

            // Mahasiswa — Teknik Informatika
            ['nama' => 'Budi Santoso',        'nama_anonim' => 'Bintang Malam',  'role' => 'user', 'prodi' => 'S1 Informatika',    'jurusan' => 'Teknik Informatika', 'nim' => '20240101', 'email' => 'budi@student.ac.id',     'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Anisa Putri',         'nama_anonim' => 'Daun Hijau',     'role' => 'user', 'prodi' => 'S1 Informatika',    'jurusan' => 'Teknik Informatika', 'nim' => '20240102', 'email' => 'anisa@student.ac.id',    'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Farhan Rizki',        'nama_anonim' => 'Langit Sore',    'role' => 'user', 'prodi' => 'S1 Informatika',    'jurusan' => 'Teknik Informatika', 'nim' => '20240103', 'email' => 'farhan@student.ac.id',   'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Dewi Lestari',        'nama_anonim' => null,             'role' => 'user', 'prodi' => 'S1 Informatika',    'jurusan' => 'Teknik Informatika', 'nim' => '20240104', 'email' => 'dewi@student.ac.id',     'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Riko Aditya',         'nama_anonim' => 'Hujan Pagi',     'role' => 'user', 'prodi' => 'S1 Informatika',    'jurusan' => 'Teknik Informatika', 'nim' => '20240105', 'email' => 'riko@student.ac.id',     'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Sinta Maharani',      'nama_anonim' => 'Awan Perak',     'role' => 'user', 'prodi' => 'S1 Informatika',    'jurusan' => 'Teknik Informatika', 'nim' => '20240106', 'email' => 'sinta@student.ac.id',    'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Yoga Pratama',        'nama_anonim' => null,             'role' => 'user', 'prodi' => 'D4 Rekayasa PL',    'jurusan' => 'Teknik Informatika', 'nim' => '20240107', 'email' => 'yoga@student.ac.id',     'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Nabila Azzahra',      'nama_anonim' => 'Embun Pagi',     'role' => 'user', 'prodi' => 'D4 Rekayasa PL',    'jurusan' => 'Teknik Informatika', 'nim' => '20240108', 'email' => 'nabila@student.ac.id',   'kata_sandi' => Hash::make('password123')],

            // Mahasiswa — Manajemen Bisnis
            ['nama' => 'Siti Rahma',          'nama_anonim' => 'Awan Kelabu',    'role' => 'user', 'prodi' => 'S1 Manajemen',      'jurusan' => 'Manajemen Bisnis',   'nim' => '20240201', 'email' => 'siti@student.ac.id',     'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Agus Firmansyah',     'nama_anonim' => null,             'role' => 'user', 'prodi' => 'S1 Akuntansi',      'jurusan' => 'Manajemen Bisnis',   'nim' => '20240202', 'email' => 'agus@student.ac.id',     'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Lina Oktaviani',      'nama_anonim' => 'Cahaya Bulan',   'role' => 'user', 'prodi' => 'S1 Manajemen',      'jurusan' => 'Manajemen Bisnis',   'nim' => '20240203', 'email' => 'lina@student.ac.id',     'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Dimas Prasetyo',      'nama_anonim' => null,             'role' => 'user', 'prodi' => 'S1 Akuntansi',      'jurusan' => 'Manajemen Bisnis',   'nim' => '20240204', 'email' => 'dimas@student.ac.id',    'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Ratna Sari',          'nama_anonim' => 'Angin Sejuk',    'role' => 'user', 'prodi' => 'S1 Manajemen',      'jurusan' => 'Manajemen Bisnis',   'nim' => '20240205', 'email' => 'ratna@student.ac.id',    'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Eko Saputra',         'nama_anonim' => null,             'role' => 'user', 'prodi' => 'S1 Manajemen',      'jurusan' => 'Manajemen Bisnis',   'nim' => '20240206', 'email' => 'eko@student.ac.id',      'kata_sandi' => Hash::make('password123')],

            // Mahasiswa — Teknik Mesin
            ['nama' => 'Doni Pratama',        'nama_anonim' => null,             'role' => 'user', 'prodi' => 'S1 Teknik Mesin',   'jurusan' => 'Teknik Mesin',       'nim' => '20240301', 'email' => 'doni@student.ac.id',     'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Ari Wibowo',          'nama_anonim' => 'Gunung Biru',    'role' => 'user', 'prodi' => 'S1 Teknik Mesin',   'jurusan' => 'Teknik Mesin',       'nim' => '20240302', 'email' => 'ari@student.ac.id',      'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Maya Anggraini',      'nama_anonim' => null,             'role' => 'user', 'prodi' => 'D4 Manufaktur',     'jurusan' => 'Teknik Mesin',       'nim' => '20240303', 'email' => 'maya@student.ac.id',     'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Rizal Hakim',         'nama_anonim' => 'Senja Merah',    'role' => 'user', 'prodi' => 'S1 Teknik Mesin',   'jurusan' => 'Teknik Mesin',       'nim' => '20240304', 'email' => 'rizal@student.ac.id',    'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Indah Permata',       'nama_anonim' => null,             'role' => 'user', 'prodi' => 'S1 Teknik Mesin',   'jurusan' => 'Teknik Mesin',       'nim' => '20240305', 'email' => 'indah@student.ac.id',    'kata_sandi' => Hash::make('password123')],

            // Mahasiswa — Teknik Elektro
            ['nama' => 'Galih Nugroho',       'nama_anonim' => 'Petir Kecil',    'role' => 'user', 'prodi' => 'S1 Teknik Elektro', 'jurusan' => 'Teknik Elektro',     'nim' => '20240401', 'email' => 'galih@student.ac.id',    'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Putri Handayani',     'nama_anonim' => null,             'role' => 'user', 'prodi' => 'S1 Teknik Elektro', 'jurusan' => 'Teknik Elektro',     'nim' => '20240402', 'email' => 'putri.h@student.ac.id',  'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Taufik Hidayat',      'nama_anonim' => 'Kabut Tipis',    'role' => 'user', 'prodi' => 'S1 Teknik Elektro', 'jurusan' => 'Teknik Elektro',     'nim' => '20240403', 'email' => 'taufik@student.ac.id',   'kata_sandi' => Hash::make('password123')],

            // Mahasiswa — Teknik Sipil
            ['nama' => 'Bayu Setiawan',       'nama_anonim' => null,             'role' => 'user', 'prodi' => 'S1 Teknik Sipil',   'jurusan' => 'Teknik Sipil',       'nim' => '20240501', 'email' => 'bayu@student.ac.id',     'kata_sandi' => Hash::make('password123')],
            ['nama' => 'Nur Fadilah',         'nama_anonim' => 'Pelangi Semu',   'role' => 'user', 'prodi' => 'S1 Teknik Sipil',   'jurusan' => 'Teknik Sipil',       'nim' => '20240502', 'email' => 'nur@student.ac.id',      'kata_sandi' => Hash::make('password123')],
        ];

        foreach ($penggunaData as $p) {
            DB::table('Pengguna')->updateOrInsert(
                ['email' => $p['email']],
                array_merge($p, ['dibuat_pada' => $now])
            );
        }

        $this->command->info('  ✓ 28 pengguna (1 admin, 2 konselor, 25 mahasiswa)');

        // Ambil ID admin & konselor
        $admin    = DB::table('Pengguna')->where('email', 'admin@gmail.com')->first();
        $konselor1 = DB::table('Pengguna')->where('email', 'rina.konselor@curhatin.com')->first();

        // =====================================================================
        // 2. PERASAAN — Daftar emosi untuk mood tracker
        // =====================================================================
        $perasaanData = [
            ['nama' => 'Senang',      'kategori' => 'positif'],
            ['nama' => 'Bersyukur',   'kategori' => 'positif'],
            ['nama' => 'Tenang',      'kategori' => 'positif'],
            ['nama' => 'Semangat',    'kategori' => 'positif'],
            ['nama' => 'Bangga',      'kategori' => 'positif'],
            ['nama' => 'Cemas',       'kategori' => 'negatif'],
            ['nama' => 'Sedih',       'kategori' => 'negatif'],
            ['nama' => 'Marah',       'kategori' => 'negatif'],
            ['nama' => 'Kesepian',    'kategori' => 'negatif'],
            ['nama' => 'Frustasi',    'kategori' => 'negatif'],
            ['nama' => 'Biasa Saja',  'kategori' => 'netral'],
            ['nama' => 'Bingung',     'kategori' => 'netral'],
        ];

        foreach ($perasaanData as $p) {
            DB::table('Perasaan')->updateOrInsert(['nama' => $p['nama']], $p);
        }

        $this->command->info('  ✓ 12 perasaan (5 positif, 5 negatif, 2 netral)');

        // =====================================================================
        // 3. SOAL — 21 butir soal DASS-21 (7 stres, 7 cemas, 7 depresi)
        // =====================================================================
        $soalDASS = [
            // Stres (s)
            ['pertanyaan' => 'Saya merasa sulit untuk bersantai.',                                        'kategori' => 'stres',   'bobot' => 1],
            ['pertanyaan' => 'Saya cenderung bereaksi berlebihan terhadap situasi.',                      'kategori' => 'stres',   'bobot' => 1],
            ['pertanyaan' => 'Saya merasa banyak menghabiskan energi untuk merasa cemas.',                 'kategori' => 'stres',   'bobot' => 1],
            ['pertanyaan' => 'Saya merasa mudah tersinggung.',                                            'kategori' => 'stres',   'bobot' => 1],
            ['pertanyaan' => 'Saya merasa sulit untuk tenang setelah sesuatu membuat saya kesal.',         'kategori' => 'stres',   'bobot' => 1],
            ['pertanyaan' => 'Saya tidak bisa memaklumi hal-hal yang menghalangi saya menyelesaikan tugas.','kategori' => 'stres',  'bobot' => 1],
            ['pertanyaan' => 'Saya merasa tegang.',                                                       'kategori' => 'stres',   'bobot' => 1],

            // Cemas (a)
            ['pertanyaan' => 'Saya merasa mulut kering.',                                                 'kategori' => 'cemas',   'bobot' => 1],
            ['pertanyaan' => 'Saya mengalami kesulitan bernapas (misalnya napas cepat tanpa aktivitas fisik).', 'kategori' => 'cemas', 'bobot' => 1],
            ['pertanyaan' => 'Saya mengalami gemetar (misalnya pada tangan).',                             'kategori' => 'cemas',   'bobot' => 1],
            ['pertanyaan' => 'Saya khawatir terhadap situasi dimana saya mungkin menjadi panik.',           'kategori' => 'cemas',   'bobot' => 1],
            ['pertanyaan' => 'Saya merasa hampir panik.',                                                  'kategori' => 'cemas',   'bobot' => 1],
            ['pertanyaan' => 'Saya menyadari denyut jantung saya meskipun tidak sehabis aktivitas fisik.', 'kategori' => 'cemas',   'bobot' => 1],
            ['pertanyaan' => 'Saya merasa takut tanpa alasan yang jelas.',                                 'kategori' => 'cemas',   'bobot' => 1],

            // Depresi (d)
            ['pertanyaan' => 'Saya merasa tidak ada hal yang bisa diharapkan.',                             'kategori' => 'depresi', 'bobot' => 1],
            ['pertanyaan' => 'Saya merasa sedih dan tertekan.',                                            'kategori' => 'depresi', 'bobot' => 1],
            ['pertanyaan' => 'Saya tidak bisa merasakan perasaan positif sama sekali.',                    'kategori' => 'depresi', 'bobot' => 1],
            ['pertanyaan' => 'Saya merasa tidak berharga.',                                                'kategori' => 'depresi', 'bobot' => 1],
            ['pertanyaan' => 'Saya merasa hidup tidak berarti.',                                           'kategori' => 'depresi', 'bobot' => 1],
            ['pertanyaan' => 'Saya tidak bisa menikmati hal-hal yang saya lakukan.',                       'kategori' => 'depresi', 'bobot' => 1],
            ['pertanyaan' => 'Saya merasa sulit untuk mengambil inisiatif dalam melakukan sesuatu.',        'kategori' => 'depresi', 'bobot' => 1],
        ];

        foreach ($soalDASS as $s) {
            DB::table('Soal')->updateOrInsert(
                ['pertanyaan' => $s['pertanyaan']],
                array_merge($s, ['id_user' => $admin->id_user])
            );
        }

        $this->command->info('  ✓ 21 soal DASS-21 (7 stres, 7 cemas, 7 depresi)');

        // =====================================================================
        // 4. REHAT — Konten relaksasi yang diunggah admin
        // =====================================================================
        $rehatData = [
            ['kategori' => 'meditasi',    'jenis_konten' => 'video',   'isi' => 'https://www.youtube.com/watch?v=inpok4MKVLM'],
            ['kategori' => 'meditasi',    'jenis_konten' => 'artikel', 'isi' => 'Meditasi mindfulness adalah latihan memfokuskan perhatian pada momen saat ini. Duduklah dengan nyaman, tutup mata, dan fokuskan perhatian pada napas Anda. Tarik napas dalam-dalam selama 4 detik, tahan 4 detik, hembuskan 4 detik. Ulangi selama 5 menit.'],
            ['kategori' => 'musik',       'jenis_konten' => 'audio',   'isi' => 'https://www.youtube.com/watch?v=lFcSrYw-ARY'],
            ['kategori' => 'musik',       'jenis_konten' => 'audio',   'isi' => 'https://www.youtube.com/watch?v=HQmmM_qwG4k'],
            ['kategori' => 'pernapasan',  'jenis_konten' => 'video',   'isi' => 'https://www.youtube.com/watch?v=tEmt1Znux58'],
            ['kategori' => 'pernapasan',  'jenis_konten' => 'artikel', 'isi' => 'Teknik Pernapasan 4-7-8: Tarik napas melalui hidung selama 4 detik. Tahan napas selama 7 detik. Hembuskan napas melalui mulut selama 8 detik. Teknik ini membantu menenangkan sistem saraf dan mengurangi kecemasan.'],
            ['kategori' => 'meditasi',    'jenis_konten' => 'artikel', 'isi' => 'Body Scan Meditation: Berbaring dengan nyaman. Mulai fokuskan perhatian dari ujung kaki, rasakan sensasi di setiap bagian tubuh secara perlahan hingga ke ujung kepala. Latihan ini membantu melepas ketegangan otot.'],
            ['kategori' => 'musik',       'jenis_konten' => 'audio',   'isi' => 'https://www.youtube.com/watch?v=1ZYbU82GVz4'],
        ];

        foreach ($rehatData as $r) {
            DB::table('Rehat')->updateOrInsert(
                ['isi' => $r['isi']],
                array_merge($r, ['id_user' => $admin->id_user])
            );
        }

        $this->command->info('  ✓ 8 konten rehat (meditasi, musik, pernapasan)');

        // =====================================================================
        // 5. HASIL ASESMEN — Data skrining DASS-21 dengan variasi tingkat keparahan
        // =====================================================================
        $mahasiswa = DB::table('Pengguna')->where('role', 'user')->get();

        // Skenario skor realistis (skor sudah dikalikan 2 sesuai DASS-21)
        $skenarioAsesmen = [
            // Normal
            ['skor_stress' => 8,  'skor_depresi' => 4,  'skor_cemas' => 4,  'kategori_hasil' => 'stres',   'kategori_penyebab' => null],
            ['skor_stress' => 6,  'skor_depresi' => 6,  'skor_cemas' => 6,  'kategori_hasil' => 'depresi', 'kategori_penyebab' => null],
            ['skor_stress' => 10, 'skor_depresi' => 2,  'skor_cemas' => 4,  'kategori_hasil' => 'stres',   'kategori_penyebab' => 'lainnya'],
            ['skor_stress' => 4,  'skor_depresi' => 8,  'skor_cemas' => 6,  'kategori_hasil' => 'depresi', 'kategori_penyebab' => null],
            ['skor_stress' => 12, 'skor_depresi' => 6,  'skor_cemas' => 2,  'kategori_hasil' => 'stres',   'kategori_penyebab' => null],

            // Ringan
            ['skor_stress' => 16, 'skor_depresi' => 10, 'skor_cemas' => 8,  'kategori_hasil' => 'stres',   'kategori_penyebab' => 'tugas_akhir'],
            ['skor_stress' => 18, 'skor_depresi' => 12, 'skor_cemas' => 8,  'kategori_hasil' => 'stres',   'kategori_penyebab' => 'magang'],
            ['skor_stress' => 15, 'skor_depresi' => 13, 'skor_cemas' => 9,  'kategori_hasil' => 'depresi', 'kategori_penyebab' => 'tugas_akhir'],

            // Sedang
            ['skor_stress' => 20, 'skor_depresi' => 16, 'skor_cemas' => 12, 'kategori_hasil' => 'depresi', 'kategori_penyebab' => 'tugas_akhir'],
            ['skor_stress' => 22, 'skor_depresi' => 14, 'skor_cemas' => 10, 'kategori_hasil' => 'stres',   'kategori_penyebab' => 'keduanya'],

            // Berat
            ['skor_stress' => 28, 'skor_depresi' => 22, 'skor_cemas' => 16, 'kategori_hasil' => 'stres',   'kategori_penyebab' => 'tugas_akhir'],
            ['skor_stress' => 30, 'skor_depresi' => 24, 'skor_cemas' => 18, 'kategori_hasil' => 'stres',   'kategori_penyebab' => 'tugas_akhir'],
            ['skor_stress' => 26, 'skor_depresi' => 21, 'skor_cemas' => 15, 'kategori_hasil' => 'depresi', 'kategori_penyebab' => 'magang'],

            // Sangat Berat
            ['skor_stress' => 36, 'skor_depresi' => 30, 'skor_cemas' => 22, 'kategori_hasil' => 'depresi', 'kategori_penyebab' => 'tugas_akhir'],
            ['skor_stress' => 34, 'skor_depresi' => 28, 'skor_cemas' => 20, 'kategori_hasil' => 'depresi', 'kategori_penyebab' => 'keduanya'],
        ];

        $catatanPenyebab = [
            'tugas_akhir' => [
                'Deadline skripsi yang menumpuk dan bimbingan dosen yang sulit dijadwalkan.',
                'Revisi proposal yang berulang-ulang membuat saya kewalahan.',
                'Tekanan untuk lulus tepat waktu sambil mengerjakan tugas akhir.',
                'Kesulitan menemukan data penelitian untuk skripsi.',
            ],
            'magang'      => [
                'Beban kerja di tempat magang sangat berat dengan jam lembur.',
                'Lingkungan magang tidak mendukung dan kurang bimbingan.',
                'Sulit menyeimbangkan magang dengan tugas kuliah yang masih berjalan.',
            ],
            'keduanya'    => [
                'Harus menyelesaikan skripsi sambil magang full-time, sangat melelahkan.',
                'Deadline magang bertabrakan dengan bimbingan tugas akhir.',
            ],
            'lainnya'     => [
                'Masalah keluarga yang cukup menganggu konsentrasi belajar.',
                'Tekanan finansial dan biaya kuliah yang memberatkan.',
            ],
        ];

        $countAsesmen = 0;
        foreach ($mahasiswa as $i => $mhs) {
            $skenario = $skenarioAsesmen[$i % count($skenarioAsesmen)];
            $tanggal  = $now->copy()->subDays(rand(1, 60));

            $catatan = null;
            if ($skenario['kategori_penyebab'] && isset($catatanPenyebab[$skenario['kategori_penyebab']])) {
                $arr     = $catatanPenyebab[$skenario['kategori_penyebab']];
                $catatan = $arr[array_rand($arr)];
            }

            DB::table('Hasil_Assesmen')->updateOrInsert(
                ['id_user' => $mhs->id_user, 'tanggal' => $tanggal],
                [
                    'kategori_hasil'    => $skenario['kategori_hasil'],
                    'skor_stress'       => $skenario['skor_stress'],
                    'skor_depresi'      => $skenario['skor_depresi'],
                    'skor_cemas'        => $skenario['skor_cemas'],
                    'kategori_penyebab' => $skenario['kategori_penyebab'],
                    'catatan_penyebab'  => $catatan,
                ]
            );
            $countAsesmen++;

            // Beberapa mahasiswa punya 2 sesi asesmen (skrining ulang)
            if ($i % 4 === 0) {
                $skenario2 = $skenarioAsesmen[($i + 3) % count($skenarioAsesmen)];
                $tanggal2  = $now->copy()->subDays(rand(61, 120));

                $catatan2 = null;
                if ($skenario2['kategori_penyebab'] && isset($catatanPenyebab[$skenario2['kategori_penyebab']])) {
                    $arr2     = $catatanPenyebab[$skenario2['kategori_penyebab']];
                    $catatan2 = $arr2[array_rand($arr2)];
                }

                DB::table('Hasil_Assesmen')->updateOrInsert(
                    ['id_user' => $mhs->id_user, 'tanggal' => $tanggal2],
                    [
                        'kategori_hasil'    => $skenario2['kategori_hasil'],
                        'skor_stress'       => $skenario2['skor_stress'],
                        'skor_depresi'      => $skenario2['skor_depresi'],
                        'skor_cemas'        => $skenario2['skor_cemas'],
                        'kategori_penyebab' => $skenario2['kategori_penyebab'],
                        'catatan_penyebab'  => $catatan2,
                    ]
                );
                $countAsesmen++;
            }
        }

        $this->command->info("  ✓ {$countAsesmen} hasil asesmen DASS-21");

        // =====================================================================
        // 6. DETAIL JAWABAN — jawaban tiap soal untuk beberapa sesi asesmen
        // =====================================================================
        $allSoal = DB::table('Soal')->get();
        $sesiAsesmen = DB::table('Hasil_Assesmen')->limit(10)->get();
        $countDetail = 0;

        foreach ($sesiAsesmen as $sesi) {
            foreach ($allSoal as $soal) {
                // Skor per item 0-3 (sesuai skala Likert DASS-21)
                $jawabanSkor = rand(0, 3);
                DB::table('Detail_Jawaban')->updateOrInsert(
                    ['id_sesi' => $sesi->id_sesi, 'id_soal' => $soal->id_soal],
                    ['jawaban_skor' => $jawabanSkor]
                );
                $countDetail++;
            }
        }

        $this->command->info("  ✓ {$countDetail} detail jawaban soal");

        // =====================================================================
        // 7. MOODTRACKER — catatan mood harian mahasiswa
        // =====================================================================
        $allPerasaan = DB::table('Perasaan')->get();
        $journals = [
            'Hari ini lumayan, bisa fokus mengerjakan tugas.',
            'Merasa cemas menjelang presentasi besok.',
            'Bersyukur hari ini berjalan lancar.',
            'Agak sedih karena teman tidak mengerti perasaan saya.',
            'Senang bisa jalan-jalan sebentar setelah kuliah.',
            'Merasa tertekan dengan deadline yang menumpuk.',
            'Hari yang biasa saja, tidak ada yang spesial.',
            'Frustasi karena kode program error terus.',
            'Tenang setelah meditasi pagi ini.',
            'Kesepian di kosan, rindu rumah.',
            null, null, // beberapa tanpa journal
        ];

        $countMood = 0;
        foreach ($mahasiswa->take(15) as $mhs) {
            $jumlahHari = rand(5, 14);
            for ($d = 0; $d < $jumlahHari; $d++) {
                $tanggal  = $now->copy()->subDays($d)->toDateString();
                $perasaan = $allPerasaan->random();
                $journal  = $journals[array_rand($journals)];

                DB::table('Moodtracker')->updateOrInsert(
                    ['id_user' => $mhs->id_user, 'tanggal' => $tanggal],
                    [
                        'id_perasaan' => $perasaan->id_perasaan,
                        'journal'     => $journal,
                    ]
                );
                $countMood++;
            }
        }

        $this->command->info("  ✓ {$countMood} entri mood tracker");

        // =====================================================================
        // 8. KONSULTASI CHATBOT — percakapan mahasiswa dengan chatbot
        // =====================================================================
        $chatbotConversations = [
            ['user' => 'Halo, saya merasa sangat cemas akhir-akhir ini.',            'bot' => 'Halo! Terima kasih sudah berbagi. Bisakah kamu ceritakan lebih lanjut apa yang membuatmu cemas?'],
            ['user' => 'Saya takut gagal dalam ujian akhir.',                         'bot' => 'Perasaan takut gagal itu wajar. Sudahkah kamu mencoba membuat jadwal belajar yang teratur? Itu bisa membantu mengurangi kecemasan.'],
            ['user' => 'Bagaimana cara mengatasi stres karena tugas akhir?',          'bot' => 'Beberapa cara yang bisa membantu: 1) Bagi tugas besar menjadi bagian kecil, 2) Istirahat teratur, 3) Olahraga ringan, 4) Bicara dengan teman atau konselor. Mau coba yang mana?'],
            ['user' => 'Saya sering tidak bisa tidur karena kepikiran banyak hal.',   'bot' => 'Insomnia sering berkaitan dengan kecemasan. Coba teknik relaksasi sebelum tidur, hindari gadget 1 jam sebelum tidur, dan atur jadwal tidur yang konsisten.'],
            ['user' => 'Terima kasih, saya akan mencobanya.',                         'bot' => 'Sama-sama! Ingat, kamu tidak sendiri. Jika merasa perlu, jangan ragu untuk konsultasi dengan konselor profesional kami. Semangat! 💪'],
        ];

        $countChat = 0;
        foreach ($mahasiswa->take(8) as $mhs) {
            $waktuBase = $now->copy()->subDays(rand(1, 30));
            $numPairs  = rand(2, count($chatbotConversations));

            for ($c = 0; $c < $numPairs; $c++) {
                $conv  = $chatbotConversations[$c % count($chatbotConversations)];
                $waktu = $waktuBase->copy()->addMinutes($c * 2);

                DB::table('Konsultasi_Chatbot')->insert([
                    'id_user'  => $mhs->id_user,
                    'pesan'    => $conv['user'],
                    'pengirim' => 'user',
                    'waktu'    => $waktu,
                ]);
                DB::table('Konsultasi_Chatbot')->insert([
                    'id_user'  => $mhs->id_user,
                    'pesan'    => $conv['bot'],
                    'pengirim' => 'bot',
                    'waktu'    => $waktu->copy()->addSeconds(rand(3, 15)),
                ]);
                $countChat += 2;
            }
        }

        $this->command->info("  ✓ {$countChat} pesan chatbot");

        // =====================================================================
        // 9. FORUM DISKUSI ANONIM — posting dan balasan anonim
        // =====================================================================
        $forumPosts = [
            ['kategori' => 'tugas_akhir', 'isi' => 'Ada yang pernah merasa sangat overwhelmed saat mengerjakan skripsi? Rasanya seperti tidak ada habisnya...'],
            ['kategori' => 'magang',      'isi' => 'Magang di perusahaan besar ternyata berat banget. Jam kerjanya panjang dan saya merasa tidak dihargai.'],
            ['kategori' => 'umum',        'isi' => 'Bagaimana cara kalian mengatasi rasa kesepian di perantauan? Saya baru semester 3 dan masih belum punya teman dekat.'],
            ['kategori' => 'tugas_akhir', 'isi' => 'Dosen pembimbing saya sangat sibuk dan sulit ditemui. Ada tips untuk mengelola bimbingan skripsi yang efektif?'],
            ['kategori' => 'umum',        'isi' => 'Sering merasa imposter syndrome, seperti tidak pantas ada di sini. Ada yang merasakan hal yang sama?'],
            ['kategori' => 'magang',      'isi' => 'Magang sambil kuliah itu sangat melelahkan. Apakah wajar kalau saya merasa burnout?'],
        ];

        $forumReplies = [
            'Saya juga merasakan hal yang sama. Kamu tidak sendiri! 🤗',
            'Coba bicara dengan konselor kampus, mereka sangat membantu.',
            'Yang penting jangan lupa istirahat dan jaga kesehatan mental.',
            'Saya pernah di posisi yang sama. Pelan-pelan aja, nanti juga bisa kok.',
            'Terima kasih sudah berbagi, ini membantu saya merasa tidak sendirian.',
            'Semangat! Kita semua pasti bisa melewati ini bersama-sama. 💪',
        ];

        $countForum = 0;
        foreach ($forumPosts as $idx => $post) {
            $author  = $mahasiswa->random();
            $waktu   = $now->copy()->subDays(rand(1, 45))->subHours(rand(0, 12));

            $postId = DB::table('Forum_Diskusi_Anonim')->insertGetId([
                'id_user'       => $author->id_user,
                'id_induk'      => null,
                'kategori'      => $post['kategori'],
                'isi_pesan'     => $post['isi'],
                'tanggal_waktu' => $waktu,
            ]);
            $countForum++;

            // 2-4 balasan per post
            $numReplies = rand(2, 4);
            for ($r = 0; $r < $numReplies; $r++) {
                $replier = $mahasiswa->random();
                DB::table('Forum_Diskusi_Anonim')->insert([
                    'id_user'       => $replier->id_user,
                    'id_induk'      => $postId,
                    'kategori'      => $post['kategori'],
                    'isi_pesan'     => $forumReplies[array_rand($forumReplies)],
                    'tanggal_waktu' => $waktu->copy()->addMinutes(rand(10, 300)),
                ]);
                $countForum++;
            }
        }

        $this->command->info("  ✓ {$countForum} posting & balasan forum anonim");

        // =====================================================================
        // 10. JADWAL SESI — jadwal konsultasi tatap muka
        // =====================================================================
        $jadwalData = [];
        for ($d = 0; $d < 14; $d++) {
            $tanggal = $now->copy()->addDays($d)->toDateString();
            $dayOfWeek = Carbon::parse($tanggal)->dayOfWeek;

            // Senin-Jumat saja (1-5)
            if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                $jadwalData[] = ['tanggal' => $tanggal, 'jam_mulai' => '09:00', 'jam_selesai' => '10:00', 'kuota_maksimal' => 6];
                $jadwalData[] = ['tanggal' => $tanggal, 'jam_mulai' => '10:00', 'jam_selesai' => '11:00', 'kuota_maksimal' => 6];
                $jadwalData[] = ['tanggal' => $tanggal, 'jam_mulai' => '13:00', 'jam_selesai' => '14:00', 'kuota_maksimal' => 4];
                $jadwalData[] = ['tanggal' => $tanggal, 'jam_mulai' => '14:00', 'jam_selesai' => '15:00', 'kuota_maksimal' => 4];
            }
        }

        foreach ($jadwalData as $j) {
            DB::table('Jadwal_Sesi')->updateOrInsert(
                ['tanggal' => $j['tanggal'], 'jam_mulai' => $j['jam_mulai']],
                $j
            );
        }

        $this->command->info('  ✓ ' . count($jadwalData) . ' jadwal sesi konsultasi (2 minggu ke depan)');

        // =====================================================================
        // 11. SESI KONSULTASI — booking konsultasi mahasiswa
        // =====================================================================
        $jadwalIds     = DB::table('Jadwal_Sesi')->pluck('id_jadwal')->toArray();
        $statusOptions = ['menunggu', 'berlangsung', 'selesai', 'dibatalkan'];
        $catatanKonsultasi = [
            'Mahasiswa menunjukkan gejala kecemasan terkait tugas akhir.',
            'Perlu follow-up minggu depan untuk memantau perkembangan.',
            'Mahasiswa mulai menunjukkan perbaikan setelah sesi ketiga.',
            'Disarankan untuk mengikuti kegiatan relaksasi rutin.',
            null, null,
        ];

        $countSesi = 0;
        foreach ($mahasiswa->take(10) as $mhs) {
            if (empty($jadwalIds)) break;

            $jadwalId = $jadwalIds[array_rand($jadwalIds)];
            $status   = $statusOptions[array_rand($statusOptions)];

            DB::table('Sesi_Konsultasi')->insert([
                'id_jadwal'  => $jadwalId,
                'id_user'    => $mhs->id_user,
                'id_konselor'=> ($status === 'selesai' || $status === 'berlangsung') ? $konselor1->id_user : null,
                'status'     => $status,
                'catatan'    => $catatanKonsultasi[array_rand($catatanKonsultasi)],
            ]);
            $countSesi++;
        }

        $this->command->info("  ✓ {$countSesi} sesi konsultasi");

        // =====================================================================
        // 12. PESAN KONSULTASI — pesan dalam sesi konsultasi yang berlangsung/selesai
        // =====================================================================
        $sesiAktif = DB::table('Sesi_Konsultasi')
            ->whereIn('status', ['berlangsung', 'selesai'])
            ->get();

        $pesanTemplate = [
            ['pengirim' => 'user',     'isi' => 'Selamat siang Bu, saya ingin konsultasi tentang masalah yang saya alami akhir-akhir ini.'],
            ['pengirim' => 'konselor', 'isi' => 'Selamat siang. Silakan ceritakan, saya siap mendengarkan.'],
            ['pengirim' => 'user',     'isi' => 'Saya merasa sangat tertekan dengan tugas dan deadline yang menumpuk.'],
            ['pengirim' => 'konselor', 'isi' => 'Saya mengerti perasaanmu. Apakah kamu sudah mencoba membuat prioritas dari tugas-tugas tersebut?'],
            ['pengirim' => 'user',     'isi' => 'Belum Bu, saya bingung harus mulai dari mana.'],
            ['pengirim' => 'konselor', 'isi' => 'Mari kita coba buat daftar prioritas bersama. Mulai dari yang deadlinenya paling dekat.'],
        ];

        $countPesan = 0;
        foreach ($sesiAktif as $sesi) {
            $waktuBase = $now->copy()->subDays(rand(1, 7));
            $numPesan  = rand(3, count($pesanTemplate));

            for ($p = 0; $p < $numPesan; $p++) {
                $tmpl      = $pesanTemplate[$p % count($pesanTemplate)];
                $pengirim  = $tmpl['pengirim'] === 'konselor' ? $konselor1->id_user : $sesi->id_user;

                DB::table('Pesan_Konsultasi')->insert([
                    'id_sesi_konsultasi' => $sesi->id_sesi_konsultasi,
                    'id_pengirim'        => $pengirim,
                    'isi_pesan'          => $tmpl['isi'],
                    'waktu_kirim'        => $waktuBase->copy()->addMinutes($p * 3),
                ]);
                $countPesan++;
            }
        }

        $this->command->info("  ✓ {$countPesan} pesan konsultasi");

        // =====================================================================
        // 13. NOTIFIKASI — notifikasi untuk pengguna
        // =====================================================================
        $notifData = [
            ['jenis' => 'pengingat_mood',    'isi' => 'Hai! Jangan lupa catat mood kamu hari ini ya 🌈'],
            ['jenis' => 'pengingat_mood',    'isi' => 'Sudahkah kamu mengisi mood tracker hari ini? Yuk dicatat!'],
            ['jenis' => 'jam_operasional',   'isi' => 'Layanan konsultasi tersedia hari Senin-Jumat, 09:00-15:00 WIB.'],
            ['jenis' => 'jam_operasional',   'isi' => 'Jadwal konsultasi minggu depan sudah tersedia. Segera booking ya!'],
        ];

        $countNotif = 0;
        foreach ($mahasiswa->take(12) as $mhs) {
            $notif = $notifData[array_rand($notifData)];
            DB::table('Notifikasi')->insert([
                'id_user'             => $mhs->id_user,
                'jenis'               => $notif['jenis'],
                'isi'                 => $notif['isi'],
                'id_sesi_konsultasi'  => null,
                'waktu_kirim'         => $now->copy()->subHours(rand(1, 72)),
                'sudah_dibaca'        => (bool) rand(0, 1),
            ]);
            $countNotif++;
        }

        // Notifikasi balasan konselor untuk sesi aktif
        foreach ($sesiAktif as $sesi) {
            DB::table('Notifikasi')->insert([
                'id_user'             => $sesi->id_user,
                'jenis'               => 'balasan_konselor',
                'isi'                 => 'Konselor telah membalas pesan di sesi konsultasi Anda.',
                'id_sesi_konsultasi'  => $sesi->id_sesi_konsultasi,
                'waktu_kirim'         => $now->copy()->subHours(rand(1, 24)),
                'sudah_dibaca'        => false,
            ]);
            $countNotif++;
        }

        $this->command->info("  ✓ {$countNotif} notifikasi");

        $this->command->newLine();
        $this->command->info('Semua dummy data berhasil di-seed!');
    }
}
