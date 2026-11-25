<?php

namespace App\Services;

use App\Exceptions\ForumException;
use App\Models\ForumDiskusiAnonim;
use App\Models\ForumLike;
use App\Models\LaporanForum;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;

class ForumService
{
    public function __construct(
        protected SanksiService $sanksiService
    ) {}

    /**
     * Buat postingan utama forum baru.
     */
    public function buatPost(Pengguna $user, array $data): ForumDiskusiAnonim
    {
        $this->pastikanTidakBersanksi($user->id_user);

        $post = ForumDiskusiAnonim::create([
            'id_user'       => $user->id_user,
            'id_induk'      => null,
            'id_komunitas'  => $data['id_komunitas'],
            'judul'         => $data['judul'],
            'isi_pesan'     => $data['isi_pesan'],
            'kategori'      => $data['kategori'],
            'jumlah_like'   => 0,
            'tanggal_waktu' => now(),
            'status'        => 'aktif',
        ]);

        return $this->muatDetailPost($post, $user->id_user);
    }

    /**
     * Buat balasan untuk postingan utama yang berstatus aktif.
     */
    public function buatBalasan(Pengguna $user, int $idPostInduk, array $data): ForumDiskusiAnonim
    {
        $this->pastikanTidakBersanksi($user->id_user);

        $postInduk = ForumDiskusiAnonim::query()
            ->where('id_post', $idPostInduk)
            ->whereNull('id_induk')
            ->where('status', 'aktif')
            ->first();

        if (! $postInduk) {
            throw new ForumException('Postingan tidak ditemukan atau sudah tidak aktif.', 404);
        }

        $balasan = ForumDiskusiAnonim::create([
            'id_user'       => $user->id_user,
            'id_induk'      => $postInduk->id_post,
            'id_komunitas'  => $postInduk->id_komunitas,
            'judul'         => null,
            'isi_pesan'     => $data['isi_pesan'],
            'kategori'      => $postInduk->kategori,
            'jumlah_like'   => 0,
            'tanggal_waktu' => now(),
            'status'        => 'aktif',
        ]);

        return $this->muatDetailPost($balasan, $user->id_user);
    }

    /**
     * Tambahkan like pada postingan aktif secara atomik.
     */
    public function likePost(Pengguna $user, int $idPost): ForumDiskusiAnonim
    {
        $this->pastikanTidakBersanksi($user->id_user);

        $post = DB::transaction(function () use ($user, $idPost) {
            $post = $this->kunciPostAktif($idPost);

            $sudahLike = ForumLike::where('id_post', $post->id_post)
                ->where('id_user', $user->id_user)
                ->exists();

            if ($sudahLike) {
                throw new ForumException('Anda sudah menyukai postingan ini.', 422);
            }

            ForumLike::create([
                'id_post'     => $post->id_post,
                'id_user'     => $user->id_user,
                'dibuat_pada' => now(),
            ]);

            $post->increment('jumlah_like');

            return $post;
        });

        return $this->muatDetailPost($post, $user->id_user);
    }

    /**
     * Hapus like pada postingan aktif secara atomik.
     */
    public function unlikePost(Pengguna $user, int $idPost): ForumDiskusiAnonim
    {
        $post = DB::transaction(function () use ($user, $idPost) {
            $post = $this->kunciPostAktif($idPost);

            $like = ForumLike::where('id_post', $post->id_post)
                ->where('id_user', $user->id_user)
                ->first();

            if (! $like) {
                throw new ForumException('Anda belum menyukai postingan ini.', 422);
            }

            $like->delete();

            if ($post->jumlah_like > 0) {
                $post->decrement('jumlah_like');
            }

            return $post;
        });

        return $this->muatDetailPost($post, $user->id_user);
    }

    /**
     * Laporkan postingan atau balasan yang berstatus aktif.
     */
    public function laporkanPost(Pengguna $user, int $idPost, array $data): LaporanForum
    {
        $this->pastikanTidakBersanksi($user->id_user);

        $post = ForumDiskusiAnonim::query()
            ->where('id_post', $idPost)
            ->where('status', 'aktif')
            ->first();

        if (! $post) {
            throw new ForumException('Konten tidak ditemukan atau sudah tidak aktif.', 404);
        }

        if ((int) $post->id_user === (int) $user->id_user) {
            throw new ForumException('Anda tidak dapat melaporkan konten milik sendiri.', 422);
        }

        $sudahLapor = LaporanForum::where('id_post', $post->id_post)
            ->where('id_pelapor', $user->id_user)
            ->exists();

        if ($sudahLapor) {
            throw new ForumException('Anda sudah pernah melaporkan konten ini sebelumnya.', 422);
        }

        return LaporanForum::create([
            'id_post'     => $post->id_post,
            'id_pelapor'  => $user->id_user,
            'kategori'    => $data['kategori'],
            'alasan'      => $data['alasan'] ?? null,
            'status'      => 'menunggu',
            'dibuat_pada' => now(),
        ]);
    }

    protected function pastikanTidakBersanksi(int $idUser): void
    {
        $sanksi = $this->sanksiService->getSanksiAktif($idUser);

        if ($sanksi) {
            throw new ForumException($this->sanksiService->pesanSanksiAktif($sanksi), 403);
        }
    }

    protected function kunciPostAktif(int $idPost): ForumDiskusiAnonim
    {
        $post = ForumDiskusiAnonim::query()
            ->where('id_post', $idPost)
            ->where('status', 'aktif')
            ->lockForUpdate()
            ->first();

        if (! $post) {
            throw new ForumException('Postingan tidak ditemukan atau sudah tidak aktif.', 404);
        }

        return $post;
    }

    protected function muatDetailPost(ForumDiskusiAnonim $post, int $idUser): ForumDiskusiAnonim
    {
        $post->load([
            'komunitas:id_komunitas,nama',
            'user:id_user,nama_anonim',
        ]);

        $post->loadCount([
            'balasan as jumlah_balasan' => fn ($query) => $query->where('status', 'aktif'),
        ]);

        $post->loadExists([
            'likes as sudah_like' => fn ($query) => $query->where('id_user', $idUser),
        ]);

        return $post;
    }
}
