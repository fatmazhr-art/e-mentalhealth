<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Forum_Diskusi_Anonim', function (Blueprint $table) {
            $table->id('id_post');
            $table->foreignId('id_user')->constrained('Pengguna', 'id_user')
                ->cascadeOnDelete()->cascadeOnUpdate(); // penulis asli, hanya di backend
            $table->unsignedBigInteger('id_induk')->nullable(); // NULL = post utama, terisi = balasan
            $table->enum('kategori', ['magang', 'tugas_akhir', 'umum'])->default('umum');
            $table->text('isi_pesan');
            $table->dateTime('tanggal_waktu')->useCurrent();

            // hapus post induk ikut menghapus balasannya (sesuai keputusan hard delete)
            $table->foreign('id_induk')->references('id_post')->on('Forum_Diskusi_Anonim')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Forum_Diskusi_Anonim');
    }
};
