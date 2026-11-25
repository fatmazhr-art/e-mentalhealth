<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KomunitasForum extends Model
{
    protected $table = 'komunitas_forum';
    protected $primaryKey = 'id_komunitas';
    public $timestamps = false;

    protected $guarded = [];

    public function posts(): HasMany
    {
        return $this->hasMany(ForumDiskusiAnonim::class, 'id_komunitas', 'id_komunitas');
    }
}
