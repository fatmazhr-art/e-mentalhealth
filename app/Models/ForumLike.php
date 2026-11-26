<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumLike extends Model
{
    protected $table = 'forum_likes';
    protected $primaryKey = 'id_like';
    public $timestamps = false;

    protected $guarded = [];

    public function post(): BelongsTo
    {
        return $this->belongsTo(ForumDiskusiAnonim::class, 'id_post', 'id_post');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_user');
    }
}
