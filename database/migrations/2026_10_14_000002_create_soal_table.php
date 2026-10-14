<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Soal', function (Blueprint $table) {
            $table->id('id_soal');
            $table->foreignId('id_user')->constrained('Pengguna', 'id_user')
                ->restrictOnDelete()->cascadeOnUpdate(); // admin pembuat soal
            $table->text('pertanyaan');
            $table->enum('kategori', ['stres', 'cemas', 'depresi']);
            $table->integer('bobot')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Soal');
    }
};
