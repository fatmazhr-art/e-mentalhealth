<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rehat extends Model
{
    protected $table = 'rehat';
    protected $primaryKey = 'id_rehat';
    public $timestamps = false;

    protected $guarded = [];
}
