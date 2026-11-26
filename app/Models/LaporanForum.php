<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanForum extends Model
{
    protected $table = 'laporan_forum';
    protected $primaryKey = 'id_laporan';
    public $timestamps = false;

    protected $guarded = [];

    public function post(): BelongsTo
    {
        return $this->belongsTo(ForumDiskusiAnonim::class, 'id_post', 'id_post');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pelapor', 'id_user');
    }

    public function penindak(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_penindak', 'id_user');
    }
}
