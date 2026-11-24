<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalSesi extends Model
{
    protected $table = 'jadwal_sesi';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = false;

    protected $guarded = [];
}
