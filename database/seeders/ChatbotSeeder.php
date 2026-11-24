<?php

namespace Database\Seeders;

use App\Models\KonsultasiChatbot;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class ChatbotSeeder extends Seeder
{
    public function run(): void
    {
        $users = Pengguna::where('role', 'user')->orderBy('id_user')->take(2)->get();

        $percakapan = [
            ['user', 'Halo, aku lagi merasa cemas banget soal ujian minggu depan.'],
            ['bot',  'Halo, terima kasih sudah bercerita. Apa yang paling kamu khawatirkan dari ujian itu?'],
            ['user', 'Takut nggak sempat belajar semua materi.'],
            ['bot',  'Wajar merasa begitu. Coba bagi materi jadi bagian kecil dan mulai dari yang paling mudah.'],
            ['user', 'Oke, aku coba. Makasih ya.'],
            ['bot',  'Sama-sama. Kalau butuh teman cerita lagi, aku di sini.'],
        ];

        foreach ($users as $user) {
            foreach ($percakapan as $menit => [$pengirim, $pesan]) {
                KonsultasiChatbot::create([
                    'id_user'  => $user->id_user,
                    'pesan'    => $pesan,
                    'pengirim' => $pengirim,
                    'waktu'    => now()->subDay()->addMinutes($menit),
                ]);
            }
        }
    }
}
