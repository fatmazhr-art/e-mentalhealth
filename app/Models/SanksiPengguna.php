<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanksiPengguna extends Model
{
    protected $table = 'sanksi_pengguna';
    protected $primaryKey = 'id_sanksi';
    public $timestamps = false;

    protected $guarded = [];
}
