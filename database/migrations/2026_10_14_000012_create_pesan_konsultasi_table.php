<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Pesan_Konsultasi', function (Blueprint $table) {
            $table->id('id_pesan');
            $table->foreignId('id_sesi_konsultasi')->constrained('Sesi_Konsultasi', 'id_sesi_konsultasi')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_pengirim')->constrained('Pengguna', 'id_user')
                ->cascadeOnDelete()->cascadeOnUpdate(); // mahasiswa atau konselor
            $table->text('isi_pesan');
            $table->dateTime('waktu_kirim')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Pesan_Konsultasi');
    }
};
