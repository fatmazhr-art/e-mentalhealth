<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Sesi_Konsultasi', function (Blueprint $table) {
            $table->id('id_sesi_konsultasi');
            $table->foreignId('id_jadwal')->constrained('Jadwal_Sesi', 'id_jadwal')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_user')->constrained('Pengguna', 'id_user')
                ->cascadeOnDelete()->cascadeOnUpdate(); // mahasiswa yang booking
            $table->unsignedBigInteger('id_konselor')->nullable(); // terisi saat ditangani
            $table->enum('status', ['menunggu', 'berlangsung', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->text('catatan')->nullable(); // opsional, boleh dihapus jika tidak dipakai

            $table->foreign('id_konselor')->references('id_user')->on('Pengguna')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Sesi_Konsultasi');
    }
};
