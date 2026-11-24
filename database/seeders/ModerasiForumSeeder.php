<?php

namespace Database\Seeders;

use App\Models\ForumDiskusiAnonim;
use App\Models\KomunitasForum;
use App\Models\LaporanForum;
use App\Models\Pengguna;
use App\Models\SanksiPengguna;
use Illuminate\Database\Seeder;

/**
 * Data contoh untuk halaman moderasi forum admin.
 * Dijalankan SETELAH ForumSeeder karena memakai postingan yang sudah ada.
 */
class ModerasiForumSeeder extends Seeder
{
    public function run(): void
    {
        $idAdmin = Pengguna::where('role', 'admin')->value('id_user');
        $idUser  = Pengguna::where('role', 'user')->orderBy('id_user')->pluck('id_user')->all();

        // 1) Bagi postingan ke komunitas secara bergantian
        $komunitas = KomunitasForum::orderBy('id_komunitas')->pluck('id_komunitas')->all();
        foreach (ForumDiskusiAnonim::orderBy('id_post')->get() as $i => $post) {
            $post->update(['id_komunitas' => $komunitas[$i % count($komunitas)]]);
        }

        $postingan = ForumDiskusiAnonim::whereNull('id_induk')->orderBy('id_post')->get();
        $komentar  = ForumDiskusiAnonim::whereNotNull('id_induk')->orderBy('id_post')->get();

        // Pelapor: pengguna lain selain penulis konten
        $pelapor = fn ($post, int $n) => collect($idUser)
            ->reject(fn ($id) => $id == $post->id_user)->take($n)->values();

        // 2) Laporan: komentar 1 (4 laporan: 3 ancaman + 1 perundungan)
        $k1 = $komentar[0];
        foreach ($pelapor($k1, 4) as $n => $idPelapor) {
            LaporanForum::create([
                'id_post' => $k1->id_post, 'id_pelapor' => $idPelapor,
                'kategori' => $n < 3 ? 'ancaman' : 'perundungan',
            ]);
        }

        // Komentar 2 (3 laporan perundungan)
        $k2 = $komentar[1];
        foreach ($pelapor($k2, 3) as $idPelapor) {
            LaporanForum::create(['id_post' => $k2->id_post, 'id_pelapor' => $idPelapor, 'kategori' => 'perundungan']);
        }

        // Postingan 1 (2 laporan spam)
        $p1 = $postingan[0];
        foreach ($pelapor($p1, 2) as $idPelapor) {
            LaporanForum::create(['id_post' => $p1->id_post, 'id_pelapor' => $idPelapor, 'kategori' => 'spam']);
        }

        // 3) Status konten: satu berisiko, satu dihapus (laporannya sudah ditindak)
        $postingan[1]->update(['status' => 'berisiko']);

        $komentar[2]->update(['status' => 'dihapus', 'dihapus_pada' => now()]);
        LaporanForum::create([
            'id_post' => $komentar[2]->id_post, 'id_pelapor' => $pelapor($komentar[2], 1)[0],
            'kategori' => 'perundungan', 'status' => 'ditindak',
            'id_penindak' => $idAdmin, 'ditindak_pada' => now(),
        ]);

        // 4) Sanksi
        SanksiPengguna::create([ // mute 24 jam, sisa 18 jam
            'id_user' => $k1->id_user, 'id_admin' => $idAdmin, 'jenis' => 'mute', 'alasan' => 'Ancaman',
            'mulai_pada' => now()->subHours(6), 'berakhir_pada' => now()->addHours(18),
        ]);
        foreach ([2, 1] as $hariLalu) { // dua peringatan perundungan
            SanksiPengguna::create([
                'id_user' => $k2->id_user, 'id_admin' => $idAdmin, 'jenis' => 'peringatan',
                'alasan' => 'Perundungan', 'mulai_pada' => now()->subDays($hariLalu),
            ]);
        }
        SanksiPengguna::create([ // satu peringatan spam
            'id_user' => $p1->id_user, 'id_admin' => $idAdmin, 'jenis' => 'peringatan',
            'alasan' => 'Spam', 'mulai_pada' => now()->subDays(2),
        ]);
        SanksiPengguna::create([ // blokir 7 hari, sisa 2 hari
            'id_user' => $idUser[7], 'id_admin' => $idAdmin, 'jenis' => 'blokir', 'alasan' => 'Ancaman berulang',
            'mulai_pada' => now()->subDays(5), 'berakhir_pada' => now()->addDays(2),
        ]);
    }
}
