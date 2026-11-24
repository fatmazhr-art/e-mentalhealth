<?php

namespace Database\Seeders;

use App\Models\KomunitasForum;
use Illuminate\Database\Seeder;

class KomunitasForumSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Magang & Dunia Kerja', 'Self-Growth', 'Tugas Akhir & Skripsi', 'Hubungan & Keluarga'] as $nama) {
            KomunitasForum::create(['nama' => $nama]);
        }
    }
}
