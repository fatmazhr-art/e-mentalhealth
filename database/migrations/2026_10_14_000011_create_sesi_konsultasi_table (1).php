<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_konsultasi', function (Blueprint $table) {
            $table->id('id_sesi_konsultasi');
            $table->foreignId('id_jadwal')->constrained('jadwal_sesi', 'id_jadwal')->cascadeOnDelete();
            $table->foreignId('id_konselor')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->foreignId('id_user')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->enum('status', ['menunggu', 'berlangsung', 'selesai', 'dibatalkan'])->default('menunggu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_konsultasi');
    }
};
