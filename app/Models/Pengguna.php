<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama', 'nama_anonim', 'role', 'prodi', 'jurusan', 'nim',
        'sip', 'no_hp', 'foto_profil', 'email', 'kata_sandi',
    ];

    protected $hidden = ['kata_sandi', 'kode_otp', 'otp_kedaluwarsa'];

    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }
}
