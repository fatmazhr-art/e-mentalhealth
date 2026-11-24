<?php

namespace Database\Seeders;

use App\Models\MoodTracker;
use App\Models\Pengguna;
use App\Models\Perasaan;
use Illuminate\Database\Seeder;

class MoodTrackerSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(7);

        $users    = Pengguna::where('role', 'user')->orderBy('id_user')->take(5)->get();
        $perasaan = Perasaan::pluck('id_perasaan')->all();

        $jurnal = [
            'Hari ini cukup padat, tapi berhasil menyelesaikan semua tugas.',
            'Susah tidur semalam, pikiran terus berputar.',
            'Ngobrol dengan teman bikin lebih lega.',
            'Agak kewalahan dengan deadline minggu ini.',
            'Hari yang tenang, sempat jalan sore sebentar.',
            null, // jurnal boleh kosong
        ];

        foreach ($users as $user) {
            // 7 hari terakhir, satu catatan per hari
            for ($hari = 6; $hari >= 0; $hari--) {
                MoodTracker::create([
                    'id_user'        => $user->id_user,
                    'id_perasaan'    => $perasaan[array_rand($perasaan)],
                    // otomatis terenkripsi lewat cast di model
                    'journal_cipher' => $jurnal[array_rand($jurnal)],
                    'tanggal'        => now()->subDays($hari)->toDateString(),
                ]);
            }
        }
    }
}
