<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SanksiPengguna extends Model
{
    protected $table = 'sanksi_pengguna';
    protected $primaryKey = 'id_sanksi';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'mulai_pada'    => 'datetime',
        'berakhir_pada' => 'datetime',
        'dicabut_pada'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_user');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_admin', 'id_user');
    }

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(LaporanForum::class, 'id_laporan', 'id_laporan');
    }
}
