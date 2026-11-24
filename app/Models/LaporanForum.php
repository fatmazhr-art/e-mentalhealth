<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanForum extends Model
{
    protected $table = 'laporan_forum';
    protected $primaryKey = 'id_laporan';
    public $timestamps = false;

    protected $guarded = [];
}
