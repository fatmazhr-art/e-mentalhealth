<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Detail_Jawaban', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_sesi')->constrained('Hasil_Assesmen', 'id_sesi')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_soal')->constrained('Soal', 'id_soal')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('jawaban_skor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Detail_Jawaban');
    }
};
