<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Jadwal_Sesi', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('kuota_maksimal')->default(6);
            $table->unique(['tanggal', 'jam_mulai'], 'uq_jadwal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Jadwal_Sesi');
    }
};
