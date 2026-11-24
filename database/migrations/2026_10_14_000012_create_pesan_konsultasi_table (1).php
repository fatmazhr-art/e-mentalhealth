<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesan_konsultasi', function (Blueprint $table) {
            $table->id('id_pesan');
            $table->foreignId('id_sesi_konsultasi')->constrained('sesi_konsultasi', 'id_sesi_konsultasi')->cascadeOnDelete();
            $table->foreignId('id_pengirim')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->text('isi_pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamp('waktu')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan_konsultasi');
    }
};
