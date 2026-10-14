<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Hasil_Assesmen', function (Blueprint $table) {
            $table->id('id_sesi');
            $table->foreignId('id_user')->constrained('Pengguna', 'id_user')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('tanggal')->useCurrent();
            $table->enum('kategori_hasil', ['stres', 'cemas', 'depresi']); // skala tertinggi
            $table->integer('skor_stress');
            $table->integer('skor_depresi');
            $table->integer('skor_cemas');
            $table->enum('kategori_penyebab', ['magang', 'tugas_akhir', 'keduanya', 'lainnya'])->nullable();
            $table->text('catatan_penyebab')->nullable(); // isian bebas jika "lainnya"
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Hasil_Assesmen');
    }
};
