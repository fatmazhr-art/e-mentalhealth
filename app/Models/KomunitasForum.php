<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomunitasForum extends Model
{
    protected $table = 'komunitas_forum';
    protected $primaryKey = 'id_komunitas';
    public $timestamps = false;

    protected $guarded = [];
}
