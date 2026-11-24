<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_assesmen', function (Blueprint $table) {
            $table->id('id_sesi');
            $table->foreignId('id_user')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->dateTime('tanggal')->nullable()->useCurrent();
            $table->string('kategori_hasil', 50);
            $table->string('kategori_penyebab', 100)->nullable();
            $table->integer('skor_stress');
            $table->integer('skor_depresi');
            $table->integer('skor_cemas');
            $table->text('catatan_penyebab')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_assesmen');
    }
};
