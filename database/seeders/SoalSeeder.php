<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\Soal;
use Illuminate\Database\Seeder;

class SoalSeeder extends Seeder
{
    public function run(): void
    {
        $idAdmin = Pengguna::where('role', 'admin')->value('id_user');

        // SEMENTARA: ini 9 soal contoh. Soal DASS-21 yang asli (21 butir) dari pihak dosen
        // belum diterima. Setelah ada, ganti isi $daftar ini menjadi 21 butir (7 per kategori).
        // Skala jawaban yang disarankan: 0 = tidak pernah ... 3 = sangat sering
        $daftar = [
            'stres' => [
                'Saya merasa sulit untuk bersantai.',
                'Saya mudah merasa tegang menghadapi tugas atau tenggat waktu.',
                'Saya mudah marah atau tersinggung belakangan ini.',
            ],
            'depresi' => [
                'Saya merasa sedih atau murung tanpa alasan yang jelas.',
                'Saya kehilangan minat pada hal yang biasanya saya sukai.',
                'Saya merasa tidak berharga atau gagal.',
            ],
            'cemas' => [
                'Saya merasa khawatir berlebihan terhadap hal-hal kecil.',
                'Jantung saya berdebar atau tangan berkeringat tanpa sebab fisik.',
                'Saya takut akan sesuatu yang buruk akan terjadi.',
            ],
        ];

        foreach ($daftar as $kategori => $pertanyaan) {
            foreach ($pertanyaan as $teks) {
                Soal::create([
                    'id_user'    => $idAdmin,
                    'jenis'      => 'dass',
                    'pertanyaan' => $teks,
                    'kategori'   => $kategori,
                    'bobot'      => 1,
                ]);
            }
        }

        // Soal penyebab: tidak punya kategori stres/depresi/cemas.
        // Pilihan jawabannya: tugas_akhir, magang, keluarga, pertemanan, lainnya.
        Soal::create([
            'id_user'    => $idAdmin,
            'jenis'      => 'penyebab',
            'pertanyaan' => 'Hal apa yang paling memengaruhi perasaanmu belakangan ini?',
            'kategori'   => null,
            'bobot'      => 1,
        ]);
    }
}
