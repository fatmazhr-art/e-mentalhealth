<?php

namespace Database\Seeders;

use App\Models\KategoriRehat;
use Illuminate\Database\Seeder;

class KategoriRehatSeeder extends Seeder
{
    public function run(): void
    {
        $daftar = [
            'Healing', 'Tugas Akhir', 'Magang', 'Kecemasan Karir',
            'Pernapasan', 'Relaksasi', 'Tidur', 'Motivasi',
        ];

        foreach ($daftar as $nama) {
            KategoriRehat::create(['nama' => $nama]);
        }
    }
}
