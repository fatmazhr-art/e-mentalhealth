<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesanKonsultasi extends Model
{
    protected $table = 'pesan_konsultasi';
    protected $primaryKey = 'id_pesan';
    public $timestamps = false;

    protected $guarded = [];
}
