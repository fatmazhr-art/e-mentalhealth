<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->foreignId('id_user')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->foreignId('id_sesi_konsultasi')->nullable()->constrained('sesi_konsultasi', 'id_sesi_konsultasi')->cascadeOnDelete();
            $table->string('jenis', 50);
            $table->text('isi');
            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamp('waktu_kirim')->nullable()->useCurrent();
            $table->dateTime('waktu_baca')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
