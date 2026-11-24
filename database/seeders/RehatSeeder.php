<?php

namespace Database\Seeders;

use App\Models\KategoriRehat;
use App\Models\Pengguna;
use App\Models\Rehat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RehatSeeder extends Seeder
{
    public function run(): void
    {
        $idAdmin  = Pengguna::where('role', 'admin')->value('id_user');
        $kategori = KategoriRehat::pluck('id_kategori', 'nama'); // ['Healing' => 1, ...]

        $konten = [
            // ---- Artikel ----
            [
                'jenis_konten' => 'artikel', 'status' => 'dipublikasi',
                'judul' => 'Mengenal Sindrom Imposter di Tengah Tekanan Seminar',
                'deskripsi' => 'Panduan kognitif behavioral berbasis bukti untuk mengenali bias pemikiran saat menghadapi pertanyaan dosen penguji.',
                'kreator' => 'Dr. Sari Wulandari, M.Psi.', 'gambar' => 'artikel-1.jpg',
                'url_sumber' => 'https://example.com/artikel/sindrom-imposter', 'jumlah_dilihat' => 5000,
                'kategori' => ['Healing', 'Tugas Akhir'],
            ],
            [
                'jenis_konten' => 'artikel', 'status' => 'dipublikasi',
                'judul' => 'Navigasi Hari Pertama Magang: Menghadapi Social Fatigue',
                'deskripsi' => 'Strategi praktis micro-break, regulasi stimulasi sosial, dan teknik grounding 5-4-3-2-1 untuk mengembalikan fokus.',
                'kreator' => 'Budi Santoso, M.Psi.', 'gambar' => 'artikel-2.jpg',
                'url_sumber' => 'https://example.com/artikel/hari-pertama-magang', 'jumlah_dilihat' => 3200,
                'kategori' => ['Magang', 'Kecemasan Karir'],
            ],
            [
                'jenis_konten' => 'artikel', 'status' => 'draf',
                'judul' => "Ritual 15 Menit 'Brain Dump' Sebelum Tidur",
                'deskripsi' => 'Menurunkan hiper-arousal otak saat deadline bertumpuk dengan protokol jurnal untuk mengeluarkan beban pikiran.',
                'kreator' => 'Rina Maharani, S.Psi.', 'gambar' => 'artikel-3.jpg',
                'url_sumber' => 'https://example.com/artikel/brain-dump', 'jumlah_dilihat' => 0,
                'kategori' => ['Tidur', 'Relaksasi'],
            ],

            // ---- Audio ----
            [
                'jenis_konten' => 'audio', 'status' => 'dipublikasi',
                'judul' => 'Hening Subuh di Kampus: Suara Hujan dan Gemericik Air',
                'deskripsi' => 'Rekaman atmosfer rintik hujan lembut, cocok untuk sesi belajar malam.',
                'kreator' => 'Tim Rehat', 'media_url' => 'media/audio/audio-1.mp3',
                'durasi_detik' => 2100, 'jumlah_dilihat' => 2409, 'warna' => '#4F4F4F',
                'kategori' => ['Relaksasi', 'Tidur'],
            ],
            [
                'jenis_konten' => 'audio', 'status' => 'dipublikasi',
                'judul' => 'Body Scan Meditation Sebelum Tidur',
                'deskripsi' => 'Latihan memindai tubuh dari kaki hingga kepala untuk membantu tidur lebih nyenyak.',
                'kreator' => 'Rina Maharani, S.Psi.', 'media_url' => 'media/audio/audio-2.mp3',
                'durasi_detik' => 1800, 'jumlah_dilihat' => 1520, 'warna' => '#D4505F',
                'kategori' => ['Tidur', 'Healing'],
            ],
            [
                'jenis_konten' => 'audio', 'status' => 'draf',
                'judul' => 'Gelombang Alpha 432Hz untuk Fokus Belajar',
                'deskripsi' => 'Musik latar untuk membantu fokus saat mengerjakan tugas.',
                'kreator' => 'Tim Rehat', 'media_url' => 'media/audio/audio-3.mp3',
                'durasi_detik' => 2400, 'jumlah_dilihat' => 0, 'warna' => '#00264D',
                'kategori' => ['Tugas Akhir', 'Motivasi'],
            ],

            // ---- Video ----
            [
                'jenis_konten' => 'video', 'status' => 'dipublikasi',
                'judul' => 'Praktik Grounding 5-4-3-2-1 Saat Mengalami Panik',
                'deskripsi' => 'Panduan langkah demi langkah menenangkan diri ketika panik datang tiba-tiba.',
                'kreator' => 'Dr. Sari Wulandari, M.Psi.', 'gambar' => 'video-1.jpg',
                'media_url' => 'media/video/video-1.mp4', 'durasi_detik' => 600, 'jumlah_dilihat' => 2409,
                'kategori' => ['Pernapasan', 'Kecemasan Karir'],
            ],
            [
                'jenis_konten' => 'video', 'status' => 'dipublikasi',
                'judul' => 'Peregangan Otot Progresif (PMR) untuk Meredakan Tegang',
                'deskripsi' => 'Relaksasi otot progresif yang bisa dilakukan di kamar kos.',
                'kreator' => 'Budi Santoso, M.Psi.', 'gambar' => 'video-2.jpg',
                'media_url' => 'media/video/video-2.mp4', 'durasi_detik' => 900, 'jumlah_dilihat' => 1180,
                'kategori' => ['Relaksasi'],
            ],
            [
                'jenis_konten' => 'video', 'status' => 'draf',
                'judul' => 'Psikoedukasi: Mengatasi Prokrastinasi Akademik',
                'deskripsi' => 'Memahami penyebab menunda tugas dan cara memulainya.',
                'kreator' => 'Rina Maharani, S.Psi.', 'gambar' => 'video-3.jpg',
                'media_url' => 'media/video/video-3.mp4', 'durasi_detik' => 1200, 'jumlah_dilihat' => 0,
                'kategori' => ['Tugas Akhir', 'Motivasi'],
            ],
        ];

        foreach ($konten as $item) {
            $namaKategori = $item['kategori'];
            unset($item['kategori']);

            $rehat = Rehat::create($item + ['id_user' => $idAdmin]);

            foreach ($namaKategori as $nama) {
                DB::table('rehat_kategori')->insert([
                    'id_rehat'    => $rehat->id_rehat,
                    'id_kategori' => $kategori[$nama],
                ]);
            }
        }
    }
}
