<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            // 'dass' = soal DASS-21, 'penyebab' = soal untuk menanyakan penyebab
            $table->enum('jenis', ['dass', 'penyebab'])->default('dass')->after('id_user');
            // Soal penyebab tidak punya kategori stres/depresi/cemas
            $table->enum('kategori', ['stres', 'depresi', 'cemas'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};
