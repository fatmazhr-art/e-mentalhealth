<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Rehat', function (Blueprint $table) {
            $table->id('id_rehat');
            $table->foreignId('id_user')->constrained('Pengguna', 'id_user')
                ->restrictOnDelete()->cascadeOnUpdate(); // admin pengunggah
            $table->enum('kategori', ['meditasi', 'musik', 'pernapasan']);
            $table->enum('jenis_konten', ['audio', 'video', 'artikel']);
            $table->text('isi'); // URL atau isi konten
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Rehat');
    }
};
