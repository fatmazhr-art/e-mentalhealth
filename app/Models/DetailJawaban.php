<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailJawaban extends Model
{
    protected $table = 'detail_jawaban';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $guarded = [];
}
