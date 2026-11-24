<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriRehat extends Model
{
    protected $table = 'kategori_rehat';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;

    protected $guarded = [];
}
