<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rehat', function (Blueprint $table) {
            $table->id('id_rehat');
            $table->foreignId('id_user')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->string('kategori', 50);
            $table->enum('jenis_konten', ['artikel', 'audio', 'video']);
            $table->text('isi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rehat');
    }
};
