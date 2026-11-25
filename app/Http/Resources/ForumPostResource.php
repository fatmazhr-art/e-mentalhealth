<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ForumPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $namaAnonim = $this->user?->nama_anonim;

        return [
            'id_post'        => $this->id_post,
            'judul'          => $this->judul,
            'isi_pesan'      => $this->isi_pesan,
            'kategori'       => $this->kategori,
            'komunitas'      => $this->whenLoaded('komunitas', fn () => $this->komunitas
                ? new KomunitasForumResource($this->komunitas)
                : null),
            'nama_anonim'    => filled($namaAnonim) ? $namaAnonim : 'Anonim',
            'jumlah_like'    => (int) $this->jumlah_like,
            'jumlah_balasan' => (int) ($this->jumlah_balasan ?? 0),
            'tanggal_waktu'  => $this->tanggal_waktu,
            'sudah_like'     => (bool) ($this->sudah_like ?? false),
        ];
    }
}
