<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodTracker extends Model
{
    protected $table = 'moodtracker';
    protected $primaryKey = 'id_mt';
    public $timestamps = false;

    protected $guarded = [];

    // Otomatis dienkripsi saat disimpan dan didekripsi saat dibaca (memakai APP_KEY).
    // Di kode cukup pakai $mood->journal_cipher seperti kolom biasa.
    protected function casts(): array
    {
        return [
            'journal_cipher' => 'encrypted',
        ];
    }
}
