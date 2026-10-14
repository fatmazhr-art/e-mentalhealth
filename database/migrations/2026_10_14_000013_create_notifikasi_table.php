<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->foreignId('id_user')->constrained('Pengguna', 'id_user')
                ->cascadeOnDelete()->cascadeOnUpdate(); // penerima
            $table->enum('jenis', ['pengingat_mood', 'balasan_konselor', 'jam_operasional']);
            $table->text('isi');
            $table->unsignedBigInteger('id_sesi_konsultasi')->nullable(); // diisi untuk balasan_konselor
            $table->dateTime('waktu_kirim')->useCurrent();
            $table->boolean('sudah_dibaca')->default(false);

            $table->foreign('id_sesi_konsultasi')->references('id_sesi_konsultasi')->on('Sesi_Konsultasi')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Notifikasi');
    }
};
