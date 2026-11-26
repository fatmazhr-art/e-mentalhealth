<?php

namespace App\Services;

use App\Exceptions\ForumException;
use App\Models\LaporanForum;
use App\Models\Pengguna;
use App\Models\SanksiPengguna;
use Illuminate\Support\Facades\DB;

class SanksiService
{
    /**
     * Ambil sanksi mute atau blokir yang masih aktif untuk pengguna tertentu.
     */
    public function getSanksiAktif(int $idUser): ?SanksiPengguna
    {
        return SanksiPengguna::query()
            ->where('id_user', $idUser)
            ->whereIn('jenis', ['mute', 'blokir'])
            ->where('status', 'aktif')
            ->where(function ($query) {
                $query->whereNull('berakhir_pada')
                    ->orWhere('berakhir_pada', '>', now());
            })
            ->orderByRaw("CASE WHEN jenis = 'blokir' THEN 0 ELSE 1 END")
            ->orderByDesc('id_sanksi')
            ->first();
    }

    /**
     * Cek apakah pengguna sedang terkena sanksi mute atau blokir aktif.
     */
    public function punyaSanksiAktif(int $idUser): bool
    {
        return $this->getSanksiAktif($idUser) !== null;
    }

    /**
     * Susun pesan penolakan berbahasa Indonesia untuk sanksi aktif.
     */
    public function pesanSanksiAktif(SanksiPengguna $sanksi): string
    {
        $label = $sanksi->jenis === 'blokir' ? 'diblokir' : 'di-mute';
        $batas = $sanksi->berakhir_pada
            ? 'hingga ' . $sanksi->berakhir_pada->format('d M Y H:i')
            : 'secara permanen';

        return "Akun Anda sedang {$label} {$batas} (alasan: {$sanksi->alasan}). Anda tidak dapat mengirim konten di forum saat ini.";
    }

    /**
     * Hitung statistik sanksi aktif untuk tab Pengguna Dibatasi.
     */
    public function getStatistikDibatasi(): array
    {
        $now = now();

        $peringatan = SanksiPengguna::query()
            ->where('jenis', 'peringatan')
            ->where('status', 'aktif')
            ->count();

        $muteAktif = SanksiPengguna::query()
            ->where('jenis', 'mute')
            ->where('status', 'aktif')
            ->where(fn ($q) => $q->whereNull('berakhir_pada')->orWhere('berakhir_pada', '>', $now))
            ->count();

        $diblokir = SanksiPengguna::query()
            ->where('jenis', 'blokir')
            ->where('status', 'aktif')
            ->where(fn ($q) => $q->whereNull('berakhir_pada')->orWhere('berakhir_pada', '>', $now))
            ->count();

        return [
            'peringatan' => $peringatan,
            'mute_aktif' => $muteAktif,
            'diblokir'   => $diblokir,
        ];
    }

    /**
     * Hitung jumlah pengguna unik yang sedang dibatasi (mute atau blokir aktif).
     */
    public function hitungPenggunaDibatasi(): int
    {
        return SanksiPengguna::query()
            ->whereIn('jenis', ['mute', 'blokir'])
            ->where('status', 'aktif')
            ->where(fn ($q) => $q->whereNull('berakhir_pada')->orWhere('berakhir_pada', '>', now()))
            ->distinct('id_user')
            ->count('id_user');
    }

    /**
     * Ambil daftar sanksi mute atau blokir yang masih aktif (tanpa nama asli/email).
     */
    public function getPenggunaDibatasi(): array
    {
        $now = now();

        $sanksiList = SanksiPengguna::query()
            ->with(['user:id_user,nama_anonim'])
            ->whereIn('jenis', ['mute', 'blokir'])
            ->where('status', 'aktif')
            ->where(fn ($q) => $q->whereNull('berakhir_pada')->orWhere('berakhir_pada', '>', $now))
            ->orderByDesc('mulai_pada')
            ->orderByDesc('id_sanksi')
            ->get();

        $userIds = $sanksiList->pluck('id_user')->unique()->all();

        $peringatanCounts = SanksiPengguna::query()
            ->whereIn('id_user', $userIds)
            ->where('jenis', 'peringatan')
            ->selectRaw('id_user, COUNT(*) as total')
            ->groupBy('id_user')
            ->pluck('total', 'id_user');

        return $sanksiList->map(function (SanksiPengguna $sanksi) use ($peringatanCounts) {
            $namaAnonim = $sanksi->user?->nama_anonim ?: 'Anonim';
            $jumlahPeringatan = (int) ($peringatanCounts[$sanksi->id_user] ?? 0);

            if ($sanksi->jenis === 'blokir') {
                $statusLabel = $sanksi->berakhir_pada ? 'Diblokir' : 'Diblokir permanen';
                $statusType  = 'blocked';
                $aksi        = $sanksi->berakhir_pada ? ['Cabut', 'Blokir permanen'] : ['Cabut'];
            } else {
                $statusLabel = 'Mute aktif';
                $statusType  = 'mute';
                $aksi        = ['Cabut', 'Perpanjang', 'Blokir'];
            }

            $berakhirText = $sanksi->berakhir_pada
                ? $sanksi->berakhir_pada->format('d M Y H:i')
                : 'Permanen';

            $sisaText = $sanksi->berakhir_pada
                ? 'berakhir ' . $sanksi->berakhir_pada->diffForHumans()
                : 'permanen';

            return [
                'id'          => $sanksi->id_sanksi,
                'id_sanksi'   => $sanksi->id_sanksi,
                'id_user'     => $sanksi->id_user,
                'nama'        => $namaAnonim,
                'jenis'       => $sanksi->jenis,
                'status'      => $statusLabel,
                'status_type' => $statusType,
                'alasan'      => $sanksi->alasan,
                'mulai'       => $sanksi->mulai_pada ? $sanksi->mulai_pada->format('d M Y H:i') : '—',
                'berakhir'    => $berakhirText,
                'sub_info'    => "Alasan: {$sanksi->alasan} · {$sisaText} · {$jumlahPeringatan} peringatan tercatat",
                'aksi'        => $aksi,
            ];
        })->values()->all();
    }

    /**
     * Berikan sanksi (peringatan, mute, atau blokir) kepada pengguna.
     * Jika berasal dari laporan, tandai laporan menunggu pada konten terkait sebagai 'ditindak'.
     */
    public function beriSanksi(int $idAdmin, array $data): SanksiPengguna
    {
        return DB::transaction(function () use ($idAdmin, $data) {
            $penggunaAda = Pengguna::where('id_user', $data['id_user'])->exists();
            if (! $penggunaAda) {
                throw new ForumException('Pengguna yang dituju tidak ditemukan.', 404);
            }

            $idLaporan = $data['id_laporan'] ?? null;
            if ($idLaporan !== null) {
                $laporan = LaporanForum::find($idLaporan);
                if (! $laporan) {
                    throw new ForumException('Data laporan tidak ditemukan.', 404);
                }

                LaporanForum::where('id_post', $laporan->id_post)
                    ->where('status', 'menunggu')
                    ->update([
                        'status'        => 'ditindak',
                        'id_penindak'   => $idAdmin,
                        'ditindak_pada' => now(),
                    ]);
            }

            $berakhirPada = null;
            if ($data['jenis'] === 'mute') {
                $berakhirPada = $data['berakhir_pada'];
            } elseif ($data['jenis'] === 'blokir' && ! empty($data['berakhir_pada'])) {
                $berakhirPada = $data['berakhir_pada'];
            }

            return SanksiPengguna::create([
                'id_user'       => $data['id_user'],
                'id_admin'      => $idAdmin,
                'id_laporan'    => $idLaporan,
                'jenis'         => $data['jenis'],
                'alasan'        => $data['alasan'],
                'mulai_pada'    => now(),
                'berakhir_pada' => $berakhirPada,
                'status'        => 'aktif',
            ]);
        });
    }

    /**
     * Cabut sanksi aktif berdasarkan ID sanksi.
     */
    public function cabutSanksi(int $idSanksi): SanksiPengguna
    {
        $sanksi = SanksiPengguna::find($idSanksi);

        if (! $sanksi) {
            throw new ForumException('Data sanksi tidak ditemukan.', 404);
        }

        if ($sanksi->status !== 'aktif') {
            throw new ForumException('Sanksi ini sudah tidak berstatus aktif.', 422);
        }

        $sanksi->update([
            'status'       => 'dicabut',
            'dicabut_pada' => now(),
        ]);

        return $sanksi;
    }
}
