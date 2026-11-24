<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perasaan extends Model
{
    protected $table = 'perasaan';
    protected $primaryKey = 'id_perasaan';
    public $timestamps = false;

    protected $guarded = [];
}
