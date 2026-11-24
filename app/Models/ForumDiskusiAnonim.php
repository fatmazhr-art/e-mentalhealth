<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumDiskusiAnonim extends Model
{
    protected $table = 'forum_diskusi_anonim';
    protected $primaryKey = 'id_post';
    public $timestamps = false;

    protected $guarded = [];
}
