<?php

namespace App\Services;

use App\Exceptions\ForumException;
use App\Models\ForumDiskusiAnonim;
use App\Models\ForumLike;
use App\Models\LaporanForum;
use App\Models\Pengguna;
use App\Models\SanksiPengguna;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    /**
     * Statistik utama halaman moderasi forum admin.
     */
    public function getStatistikModerasi(): array
    {
        return [
            'laporan_baru'     => LaporanForum::where('status', 'menunggu')->count(),
            'konten_beresiko'  => ForumDiskusiAnonim::where('status', 'berisiko')->count(),
            'dihapus_hari_ini' => ForumDiskusiAnonim::where('status', 'dihapus')->count(),
            'pengguna_dimute'  => $this->sanksiService->hitungPenggunaDibatasi(),
        ];
    }

    /**
     * Antrean laporan dikelompokkan per konten (id_post), memprioritaskan status 'menunggu'.
     */
    public function getAntreanLaporan(): array
    {
        $kontenDilaporkan = ForumDiskusiAnonim::query()
            ->with([
                'user:id_user,nama_anonim',
                'komunitas:id_komunitas,nama',
                'induk:id_post,id_user,id_komunitas,isi_pesan,tanggal_waktu',
                'induk.user:id_user,nama_anonim',
                'induk.komunitas:id_komunitas,nama',
                'laporan' => fn ($q) => $q->orderByDesc('dibuat_pada')->orderByDesc('id_laporan'),
            ])
            ->whereHas('laporan')
            ->get();

        $userIds = $kontenDilaporkan->pluck('id_user')->unique()->all();

        $riwayatPeringatan = SanksiPengguna::query()
            ->whereIn('id_user', $userIds)
            ->where('jenis', 'peringatan')
            ->selectRaw('id_user, COUNT(*) as total')
            ->groupBy('id_user')
            ->pluck('total', 'id_user');

        $riwayatMute = SanksiPengguna::query()
            ->whereIn('id_user', $userIds)
            ->where('jenis', 'mute')
            ->where('status', 'aktif')
            ->where(fn ($q) => $q->whereNull('berakhir_pada')->orWhere('berakhir_pada', '>', now()))
            ->selectRaw('id_user, COUNT(*) as total')
            ->groupBy('id_user')
            ->pluck('total', 'id_user');

        $badgeColors = [
            'ancaman'     => '#7A0C2E',
            'perundungan' => '#D97706',
            'spam'        => '#475569',
            'lainnya'     => '#6B7280',
        ];

        $sorted = $kontenDilaporkan->sortByDesc(function (ForumDiskusiAnonim $post) {
            $jumlahMenunggu = $post->laporan->where('status', 'menunggu')->count();
            $waktuTerbaru   = $post->laporan->max('dibuat_pada') ?? '';
            $prioritas      = $jumlahMenunggu > 0 ? 1 : 0;

            return sprintf('%d-%05d-%s', $prioritas, $jumlahMenunggu, $waktuTerbaru);
        })->values();

        return $sorted->map(function (ForumDiskusiAnonim $post) use ($riwayatPeringatan, $riwayatMute, $badgeColors) {
            $laporanMenunggu = $post->laporan->where('status', 'menunggu');
            $adaMenunggu     = $laporanMenunggu->isNotEmpty();
            $sumberLaporan   = $adaMenunggu ? $laporanMenunggu : $post->laporan;

            $kategoriCounts  = $sumberLaporan->countBy('kategori')->sortDesc();
            $kategoriUtama   = $kategoriCounts->keys()->first() ?? 'lainnya';
            $totalLaporan    = $sumberLaporan->count();
            $laporanPertama  = $sumberLaporan->first();
            $statusLaporan   = $adaMenunggu ? 'menunggu' : ($laporanPertama?->status ?? 'menunggu');

            $rincianKategori = $kategoriCounts
                ->map(fn ($count, $kat) => "{$count} {$kat}")
                ->implode(', ');

            $penulisAnonim = $post->user?->nama_anonim ?: 'Anonim';
            $komunitasNama = $post->komunitas?->nama ?? 'Umum';
            $waktuTampil   = $laporanPertama?->dibuat_pada
                ? Carbon::parse($laporanPertama->dibuat_pada)->format('H.i')
                : ($post->tanggal_waktu ? Carbon::parse($post->tanggal_waktu)->format('H.i') : '-');

            $jmlPeringatan = (int) ($riwayatPeringatan[$post->id_user] ?? 0);
            $jmlMuteAktif  = (int) ($riwayatMute[$post->id_user] ?? 0);

            $isKomentar = $post->id_induk !== null;

            if ($isKomentar && $post->induk) {
                $indukPenulis   = $post->induk->user?->nama_anonim ?: 'Anonim';
                $indukKomunitas = $post->induk->komunitas?->nama ?? $komunitasNama;
                $indukWaktu     = $post->induk->tanggal_waktu
                    ? Carbon::parse($post->induk->tanggal_waktu)->diffForHumans()
                    : '-';

                $postKonteks = [
                    'penulis'   => $indukPenulis,
                    'waktu'     => $indukWaktu,
                    'komunitas' => $indukKomunitas,
                    'isi'       => $post->induk->isi_pesan,
                ];

                $komentarKonteks = [
                    'penulis'  => $penulisAnonim,
                    'waktu'    => $waktuTampil,
                    'membalas' => $indukPenulis,
                    'isi'      => $post->isi_pesan,
                ];
            } else {
                $postKonteks = [
                    'penulis'   => $penulisAnonim,
                    'waktu'     => $waktuTampil,
                    'komunitas' => $komunitasNama,
                    'isi'       => $post->isi_pesan,
                ];
                $komentarKonteks = null;
            }

            $statusSubLabel = match ($statusLaporan) {
                'menunggu' => 'Menunggu tindakan',
                'ditindak' => 'Sudah ditindak',
                'ditolak'  => 'Laporan ditolak',
                default    => ucfirst($statusLaporan),
            };

            return [
                'id'              => $post->id_post,
                'id_post'         => $post->id_post,
                'id_user'         => $post->id_user,
                'id_laporan'      => $laporanPertama?->id_laporan,
                'jenis'           => $isKomentar ? 'Komentar' : 'Postingan',
                'kategori'        => ucfirst($kategoriUtama),
                'kategori_slug'   => $post->status === 'berisiko' ? 'berisiko' : $kategoriUtama,
                'badge_color'     => $badgeColors[$kategoriUtama] ?? '#6B7280',
                'waktu'           => $waktuTampil,
                'penulis'         => $penulisAnonim,
                'komunitas'       => $komunitasNama,
                'isi'             => $post->isi_pesan,
                'jumlah_laporan'  => "{$totalLaporan} laporan",
                'status_laporan'  => $statusLaporan,
                'status_konten'   => $post->status,
                'post_konteks'    => $postKonteks,
                'komentar_konteks'=> $komentarKonteks,
                'riwayat'         => "Riwayat akun {$penulisAnonim}: {$jmlPeringatan} peringatan, {$jmlMuteAktif} mute aktif. Identitas asli tidak ditampilkan kepada moderator.",
                'sub_laporan'     => "{$statusSubLabel} · {$totalLaporan} laporan ({$rincianKategori}) · Status konten: {$post->status}",
            ];
        })->all();
    }

    /**
     * Ambil seluruh konten forum (postingan utama dan balasan) dengan paginasi untuk admin.
     */
    public function getSemuaKonten(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = ForumDiskusiAnonim::query()
            ->with([
                'user:id_user,nama_anonim',
                'komunitas:id_komunitas,nama',
                'balasan' => fn ($q) => $q
                    ->with(['user:id_user,nama_anonim'])
                    ->withCount(['laporan as laporan_menunggu_count' => fn ($l) => $l->where('status', 'menunggu')])
                    ->orderBy('tanggal_waktu'),
            ])
            ->withCount([
                'balasan as komentar_count',
                'laporan as laporan_menunggu_count' => fn ($q) => $q->where('status', 'menunggu'),
            ]);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('isi_pesan', 'like', "%{$search}%")
                    ->orWhere('judul', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('nama_anonim', 'like', "%{$search}%"));
            });
        }

        if (! empty($filters['jenis'])) {
            if ($filters['jenis'] === 'Postingan') {
                $query->whereNull('id_induk');
            } elseif ($filters['jenis'] === 'Komentar') {
                $query->whereNotNull('id_induk');
            }
        }

        if (! empty($filters['id_komunitas'])) {
            $query->where('id_komunitas', $filters['id_komunitas']);
        }

        if (! empty($filters['status'])) {
            if ($filters['status'] === 'dilaporkan') {
                $query->where('status', 'aktif')
                    ->whereHas('laporan', fn ($q) => $q->where('status', 'menunggu'));
            } elseif (in_array($filters['status'], ['aktif', 'berisiko', 'dihapus'], true)) {
                $query->where('status', $filters['status']);
            }
        }

        $paginator = $query
            ->orderByDesc('tanggal_waktu')
            ->orderByDesc('id_post')
            ->paginate($perPage)
            ->withQueryString();

        $paginator->through(function (ForumDiskusiAnonim $item) {
            $penulis        = $item->user?->nama_anonim ?: 'Anonim';
            $isKomentar     = $item->id_induk !== null;
            $laporanPending = (int) $item->laporan_menunggu_count;

            if ($item->status === 'dihapus') {
                $statusLabel = 'Dihapus';
                $aksi        = ['pulihkan'];
            } elseif ($item->status === 'berisiko') {
                $statusLabel = 'Berisiko';
                $aksi        = ['tinjau', 'hapus'];
            } elseif ($laporanPending > 0) {
                $statusLabel = "Dilaporkan {$laporanPending}";
                $aksi        = ['tinjau', 'hapus'];
            } else {
                $statusLabel = 'Aktif';
                $aksi        = ['lihat', 'hapus'];
            }

            $komentarList = $item->balasan->map(function (ForumDiskusiAnonim $balasan) {
                return [
                    'id_post'    => $balasan->id_post,
                    'penulis'    => $balasan->user?->nama_anonim ?: 'Anonim',
                    'waktu'      => $balasan->tanggal_waktu ? Carbon::parse($balasan->tanggal_waktu)->diffForHumans() : '-',
                    'isi'        => $balasan->isi_pesan,
                    'is_flagged' => $balasan->laporan_menunggu_count > 0,
                    'laporan'    => (int) $balasan->laporan_menunggu_count,
                ];
            })->values()->all();

            return [
                'id'             => $item->id_post,
                'id_post'        => $item->id_post,
                'id_user'        => $item->id_user,
                'jenis'          => $isKomentar ? 'Komentar' : 'Postingan',
                'isi'            => mb_strimwidth($item->isi_pesan, 0, 80, '...'),
                'isi_full'       => $item->isi_pesan,
                'penulis'        => $penulis,
                'alias'          => $penulis,
                'komunitas'      => $item->komunitas?->nama ?? 'Umum',
                'waktu'          => $item->tanggal_waktu ? Carbon::parse($item->tanggal_waktu)->diffForHumans() : '-',
                'status'         => $statusLabel,
                'is_deleted'     => $item->status === 'dihapus',
                'dukungan'       => (int) $item->jumlah_like,
                'komentar_count' => (int) $item->komentar_count,
                'komentar_list'  => $komentarList,
                'aksi'           => $aksi,
            ];
        });

        return $paginator;
    }

    /**
     * Hapus konten forum (soft status 'dihapus') dan tindak laporan menunggu terkait.
     */
    public function hapusKonten(
        int $idPost,
        int $idAdmin,
        ?string $alasan = null,
        bool $peringatkan = false,
        bool $mute24Jam = false
    ): ForumDiskusiAnonim {
        return DB::transaction(function () use ($idPost, $idAdmin, $alasan, $peringatkan, $mute24Jam) {
            $post = ForumDiskusiAnonim::find($idPost);

            if (! $post) {
                throw new ForumException('Konten tidak ditemukan.', 404);
            }

            if ($post->status === 'dihapus') {
                throw new ForumException('Konten ini sudah berstatus dihapus.', 422);
            }

            $post->update([
                'status'       => 'dihapus',
                'dihapus_pada' => now(),
            ]);

            $laporanMenunggu = LaporanForum::where('id_post', $post->id_post)
                ->where('status', 'menunggu')
                ->orderBy('id_laporan')
                ->first();

            if ($laporanMenunggu) {
                LaporanForum::where('id_post', $post->id_post)
                    ->where('status', 'menunggu')
                    ->update([
                        'status'        => 'ditindak',
                        'id_penindak'   => $idAdmin,
                        'ditindak_pada' => now(),
                    ]);
            }

            $alasanSanksi = $alasan ?: 'Pelanggaran aturan forum';

            if ($peringatkan) {
                $this->sanksiService->beriSanksi($idAdmin, [
                    'id_user'    => $post->id_user,
                    'id_laporan' => $laporanMenunggu?->id_laporan,
                    'jenis'      => 'peringatan',
                    'alasan'     => mb_strimwidth($alasanSanksi, 0, 100),
                ]);
            }

            if ($mute24Jam) {
                $this->sanksiService->beriSanksi($idAdmin, [
                    'id_user'       => $post->id_user,
                    'id_laporan'    => $laporanMenunggu?->id_laporan,
                    'jenis'         => 'mute',
                    'alasan'        => mb_strimwidth($alasanSanksi, 0, 100),
                    'berakhir_pada' => now()->addHours(24),
                ]);
            }

            return $post;
        });
    }

    /**
     * Pulihkan konten forum yang berstatus 'dihapus' menjadi 'aktif'.
     */
    public function pulihkanKonten(int $idPost): ForumDiskusiAnonim
    {
        $post = ForumDiskusiAnonim::find($idPost);

        if (! $post) {
            throw new ForumException('Konten tidak ditemukan.', 404);
        }

        if ($post->status !== 'dihapus') {
            throw new ForumException('Konten ini tidak dalam status dihapus.', 422);
        }

        $post->update([
            'status'       => 'aktif',
            'dihapus_pada' => null,
        ]);

        return $post;
    }

    /**
     * Tandai seluruh laporan 'menunggu' pada konten tertentu sebagai 'ditindak'.
     */
    public function tindakLaporan(int $idPost, int $idAdmin): int
    {
        $post = ForumDiskusiAnonim::find($idPost);

        if (! $post) {
            throw new ForumException('Konten tidak ditemukan.', 404);
        }

        $updated = LaporanForum::where('id_post', $idPost)
            ->where('status', 'menunggu')
            ->update([
                'status'        => 'ditindak',
                'id_penindak'   => $idAdmin,
                'ditindak_pada' => now(),
            ]);

        if ($updated === 0) {
            throw new ForumException('Tidak ada laporan berstatus menunggu untuk konten ini.', 422);
        }

        return $updated;
    }

    /**
     * Tolak seluruh laporan 'menunggu' pada konten tertentu.
     */
    public function tolakLaporan(int $idPost, int $idAdmin): int
    {
        $post = ForumDiskusiAnonim::find($idPost);

        if (! $post) {
            throw new ForumException('Konten tidak ditemukan.', 404);
        }

        $updated = LaporanForum::where('id_post', $idPost)
            ->where('status', 'menunggu')
            ->update([
                'status'        => 'ditolak',
                'id_penindak'   => $idAdmin,
                'ditindak_pada' => now(),
            ]);

        if ($updated === 0) {
            throw new ForumException('Tidak ada laporan berstatus menunggu untuk konten ini.', 422);
        }

        return $updated;
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
