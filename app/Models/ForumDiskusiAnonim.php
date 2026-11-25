<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumDiskusiAnonim extends Model
{
    protected $table = 'forum_diskusi_anonim';
    protected $primaryKey = 'id_post';
    public $timestamps = false;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_user', 'id_user');
    }

    public function komunitas(): BelongsTo
    {
        return $this->belongsTo(KomunitasForum::class, 'id_komunitas', 'id_komunitas');
    }

    public function induk(): BelongsTo
    {
        return $this->belongsTo(ForumDiskusiAnonim::class, 'id_induk', 'id_post');
    }

    public function balasan(): HasMany
    {
        return $this->hasMany(ForumDiskusiAnonim::class, 'id_induk', 'id_post');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ForumLike::class, 'id_post', 'id_post');
    }
}
