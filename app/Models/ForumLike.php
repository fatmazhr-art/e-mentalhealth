<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumLike extends Model
{
    protected $table = 'forum_likes';
    protected $primaryKey = 'id_like';
    public $timestamps = false;

    protected $guarded = [];
}
