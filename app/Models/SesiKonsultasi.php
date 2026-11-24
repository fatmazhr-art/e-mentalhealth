<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiKonsultasi extends Model
{
    protected $table = 'sesi_konsultasi';
    protected $primaryKey = 'id_sesi_konsultasi';
    public $timestamps = false;

    protected $guarded = [];
}
