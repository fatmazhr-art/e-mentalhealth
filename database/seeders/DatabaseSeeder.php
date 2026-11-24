<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan penting: tabel induk dulu, baru tabel yang punya foreign key.
     * Jalankan dengan: php artisan migrate:fresh --drop-views --seed
     */
    public function run(): void
    {
        $this->call([
            PenggunaSeeder::class,        // pengguna
            SoalSeeder::class,            // soal (butuh admin)
            PerasaanSeeder::class,        // perasaan
            KategoriRehatSeeder::class,   // kategori_rehat
            RehatSeeder::class,           // rehat + rehat_kategori (butuh admin & kategori)
            KomunitasForumSeeder::class,  // komunitas_forum
            JadwalSesiSeeder::class,      // jadwal_sesi
            AssesmenSeeder::class,        // hasil_assesmen + detail_jawaban
            MoodTrackerSeeder::class,     // moodtracker
            ChatbotSeeder::class,         // konsultasi_chatbot
            ForumSeeder::class,           // forum_diskusi_anonim + forum_likes
            ModerasiForumSeeder::class,   // komunitas pada postingan, laporan_forum, sanksi_pengguna
            KonsultasiSeeder::class,      // sesi_konsultasi + pesan_konsultasi + notifikasi
        ]);
    }
}
