<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_forum', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->foreignId('id_post')->constrained('forum_diskusi_anonim', 'id_post')->cascadeOnDelete();
            $table->foreignId('id_pelapor')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->enum('kategori', ['ancaman', 'perundungan', 'spam', 'lainnya']);
            $table->text('alasan')->nullable();
            $table->enum('status', ['menunggu', 'ditindak', 'ditolak'])->default('menunggu');
            $table->foreignId('id_penindak')->nullable()->constrained('pengguna', 'id_user')->nullOnDelete(); // admin
            $table->timestamp('ditindak_pada')->nullable();
            $table->timestamp('dibuat_pada')->nullable()->useCurrent();

            $table->unique(['id_post', 'id_pelapor']); // satu pengguna melapor satu konten sekali
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_forum');
    }
};
