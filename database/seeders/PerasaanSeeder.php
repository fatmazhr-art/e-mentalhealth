<?php

namespace Database\Seeders;

use App\Models\Perasaan;
use Illuminate\Database\Seeder;

class PerasaanSeeder extends Seeder
{
    public function run(): void
    {
        $daftar = [
            ['Senang',      'positif'],
            ['Tenang',      'positif'],
            ['Bersyukur',   'positif'],
            ['Semangat',    'positif'],
            ['Biasa saja',  'netral'],
            ['Lelah',       'negatif'],
            ['Cemas',       'negatif'],
            ['Sedih',       'negatif'],
            ['Marah',       'negatif'],
            ['Kesepian',    'negatif'],
        ];

        foreach ($daftar as [$nama, $kategori]) {
            Perasaan::create(['nama' => $nama, 'kategori' => $kategori]);
        }
    }
}
