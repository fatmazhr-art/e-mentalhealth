<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KomunitasForumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_komunitas' => $this->id_komunitas,
            'nama'         => $this->nama,
        ];
    }
}
