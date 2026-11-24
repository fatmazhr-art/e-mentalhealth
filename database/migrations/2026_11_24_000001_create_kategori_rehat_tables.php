<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Daftar kategori/tag konten rehat (Healing, Tugas Akhir, dst)
        Schema::create('kategori_rehat', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama', 50)->unique();
        });

        // Satu konten rehat bisa punya banyak kategori
        Schema::create('rehat_kategori', function (Blueprint $table) {
            $table->foreignId('id_rehat')->constrained('rehat', 'id_rehat')->cascadeOnDelete();
            $table->foreignId('id_kategori')->constrained('kategori_rehat', 'id_kategori')->cascadeOnDelete();

            $table->primary(['id_rehat', 'id_kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rehat_kategori');
        Schema::dropIfExists('kategori_rehat');
    }
};
