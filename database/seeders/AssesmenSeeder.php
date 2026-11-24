<?php

namespace Database\Seeders;

use App\Models\DetailJawaban;
use App\Models\HasilAssesmen;
use App\Models\Pengguna;
use App\Models\Soal;
use Illuminate\Database\Seeder;

class AssesmenSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(42); // hasil acak selalu sama, jadi seluruh tim punya data identik

        // Hanya soal DASS yang dihitung skornya. Soal penyebab tidak punya kategori.
        $semuaSoal       = Soal::where('jenis', 'dass')->get();
        $soalPerKategori = $semuaSoal->groupBy('kategori');
        $users           = Pengguna::where('role', 'user')->orderBy('id_user')->take(5)->get();

        // Nilai kategori_penyebab harus konsisten dengan dashboard admin
        $penyebab = [
            ['tugas_akhir', 'Tugas akhir dan bimbingan menyita hampir seluruh waktu.'],
            ['magang',      'Beban magang dan adaptasi lingkungan kerja terasa berat.'],
            ['keluarga',    'Ada masalah di rumah yang cukup memengaruhi pikiran.'],
            ['pertemanan',  'Merasa kurang cocok dengan lingkungan pertemanan.'],
            ['lainnya',     'Banyak hal kecil yang menumpuk dan sulit dijelaskan.'],
        ];

        foreach ($users as $i => $user) {
            // Dua kali asesmen per user: 30 hari lalu dan 7 hari lalu
            foreach ([30, 7] as $j => $hariLalu) {
                // Skor acak 0-3 untuk tiap soal
                $jawaban = [];
                foreach ($semuaSoal as $soal) {
                    $jawaban[$soal->id_soal] = mt_rand(0, 3);
                }

                // Hitung skor per kategori = jumlah (jawaban x bobot)
                // SEMENTARA: rumus final dan batas kategori menunggu aturan dari dosen.
                $skor = ['stres' => 0, 'depresi' => 0, 'cemas' => 0];
                foreach ($soalPerKategori as $kategori => $daftarSoal) {
                    foreach ($daftarSoal as $soal) {
                        $skor[$kategori] += $jawaban[$soal->id_soal] * $soal->bobot;
                    }
                }

                // Kategori hasil sederhana (maks total = 27). Sesuaikan dengan aturan tim.
                $total = array_sum($skor);
                $kategoriHasil = $total <= 9 ? 'ringan' : ($total <= 18 ? 'sedang' : 'berat');

                [$kategoriPenyebab, $catatan] = $penyebab[($i + $j) % count($penyebab)];

                $hasil = HasilAssesmen::create([
                    'id_user'           => $user->id_user,
                    'tanggal'           => now()->subDays($hariLalu),
                    'kategori_hasil'    => $kategoriHasil,
                    'kategori_penyebab' => $kategoriPenyebab,
                    'skor_stress'       => $skor['stres'],
                    'skor_depresi'      => $skor['depresi'],
                    'skor_cemas'        => $skor['cemas'],
                    'catatan_penyebab'  => $catatan,
                ]);

                foreach ($jawaban as $idSoal => $nilai) {
                    DetailJawaban::create([
                        'id_sesi'       => $hasil->id_sesi,
                        'id_soal'       => $idSoal,
                        'jawaban_skor'  => $nilai,
                    ]);
                }
            }
        }
    }
}
