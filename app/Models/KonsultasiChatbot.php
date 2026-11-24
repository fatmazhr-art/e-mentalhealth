<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonsultasiChatbot extends Model
{
    protected $table = 'konsultasi_chatbot';
    protected $primaryKey = 'id_chatbot';
    public $timestamps = false;

    protected $guarded = [];
}
