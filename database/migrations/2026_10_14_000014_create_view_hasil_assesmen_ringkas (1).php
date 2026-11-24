<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_hasil_assesmen_ringkas AS
            SELECT
                ha.id_sesi,
                p.nama_anonim,
                p.prodi,
                ha.kategori_hasil,
                ha.skor_stress,
                ha.skor_depresi,
                ha.skor_cemas,
                ha.tanggal
            FROM hasil_assesmen ha
            JOIN pengguna p ON p.id_user = ha.id_user
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_hasil_assesmen_ringkas');
    }
};
