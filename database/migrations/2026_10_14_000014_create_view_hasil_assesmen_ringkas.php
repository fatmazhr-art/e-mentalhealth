<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // VIEW hanya membatasi KOLOM untuk akun konselor (tanpa skor detail).
        // Pembatasan BARIS (hanya mahasiswa yang terhubung sesi) tetap di backend.
        DB::statement('CREATE OR REPLACE VIEW View_Hasil_Assesmen_Ringkas AS
            SELECT id_sesi, id_user, tanggal, kategori_hasil, kategori_penyebab
            FROM Hasil_Assesmen');
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS View_Hasil_Assesmen_Ringkas');
    }
};
