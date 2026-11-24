<?php

namespace Database\Seeders;

use App\Models\ForumDiskusiAnonim;
use App\Models\ForumLike;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $idUser = Pengguna::where('role', 'user')->orderBy('id_user')->pluck('id_user')->all();

        $postingan = [
            ['Susah fokus kuliah',      'Akhir-akhir ini susah banget fokus di kelas. Ada yang punya tips?', 'akademik'],
            ['Capek sama ekspektasi',   'Kadang merasa terbebani ekspektasi keluarga. Ada yang pernah ngerasain?', 'keluarga'],
            ['Takut ngomong di depan',  'Setiap presentasi jantung berdebar kencang. Gimana cara mengatasinya?', 'akademik'],
            ['Merasa kesepian',         'Banyak teman tapi tetap merasa sendirian. Normal nggak sih?', 'umum'],
        ];

        $balasan = [
            'Aku juga pernah di posisi itu, pelan-pelan pasti membaik. Semangat!',
            'Coba ceritakan ke orang yang kamu percaya, biasanya membantu.',
            'Kamu nggak sendirian, terima kasih sudah berani cerita.',
        ];

        foreach ($postingan as $i => [$judul, $isi, $kategori]) {
            $post = ForumDiskusiAnonim::create([
                'id_user'   => $idUser[$i % count($idUser)],
                'judul'     => $judul,
                'isi_pesan' => $isi,
                'kategori'  => $kategori,
            ]);

            // 2 balasan per postingan (id_induk menunjuk ke postingan utama)
            foreach ([1, 2] as $k) {
                ForumDiskusiAnonim::create([
                    'id_user'   => $idUser[($i + $k) % count($idUser)],
                    'id_induk'  => $post->id_post,
                    'isi_pesan' => $balasan[($i + $k) % count($balasan)],
                    'kategori'  => $kategori,
                ]);
            }

            // Like dari beberapa user berbeda (unik per post + user)
            $jumlahLike = $i + 2;
            for ($k = 0; $k < $jumlahLike; $k++) {
                ForumLike::create([
                    'id_post' => $post->id_post,
                    'id_user' => $idUser[($i + $k + 3) % count($idUser)],
                ]);
            }

            // Sinkronkan kolom jumlah_like dengan data forum_likes
            $post->update(['jumlah_like' => $jumlahLike]);
        }
    }
}
