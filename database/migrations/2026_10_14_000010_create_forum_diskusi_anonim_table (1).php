<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_diskusi_anonim', function (Blueprint $table) {
            $table->id('id_post');
            $table->foreignId('id_user')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->foreignId('id_induk')->nullable()->constrained('forum_diskusi_anonim', 'id_post')->cascadeOnDelete(); // balasan
            $table->string('judul', 150)->nullable();
            $table->text('isi_pesan');
            $table->string('kategori', 50);
            $table->integer('jumlah_like')->default(0);
            $table->timestamp('tanggal_waktu')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_diskusi_anonim');
    }
};
