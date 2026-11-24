<?php

namespace Database\Seeders;

use App\Models\JadwalSesi;
use Illuminate\Database\Seeder;

class JadwalSesiSeeder extends Seeder
{
    public function run(): void
    {
        $slot = [
            ['09:00:00', '10:00:00'],
            ['13:00:00', '14:00:00'],
        ];

        // Dari 3 hari lalu sampai 10 hari ke depan (supaya ada jadwal lampau, hari ini, dan mendatang)
        for ($hari = -3; $hari <= 10; $hari++) {
            $tanggal = now()->addDays($hari)->toDateString();

            foreach ($slot as [$mulai, $selesai]) {
                JadwalSesi::create([
                    'tanggal'        => $tanggal,
                    'jam_mulai'      => $mulai,
                    'jam_selesai'    => $selesai,
                    'kuota_maksimal' => 1,
                ]);
            }
        }
    }
}
