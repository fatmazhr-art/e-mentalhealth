<?php

namespace App\Services;

use App\Models\SanksiPengguna;

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
}
