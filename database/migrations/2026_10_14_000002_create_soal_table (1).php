<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->id('id_soal');
            $table->foreignId('id_user')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->enum('kategori', ['stres', 'depresi', 'cemas']);
            $table->integer('bobot')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal');
    }
};
