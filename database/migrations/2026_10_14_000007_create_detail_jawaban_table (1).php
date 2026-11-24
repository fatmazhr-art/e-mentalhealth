<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_jawaban', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_sesi')->constrained('hasil_assesmen', 'id_sesi')->cascadeOnDelete();
            $table->foreignId('id_soal')->constrained('soal', 'id_soal')->cascadeOnDelete();
            $table->integer('jawaban_skor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_jawaban');
    }
};
