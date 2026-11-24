<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilAssesmen extends Model
{
    protected $table = 'hasil_assesmen';
    protected $primaryKey = 'id_sesi';
    public $timestamps = false;

    protected $guarded = [];
}
