<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanksi_pengguna', function (Blueprint $table) {
            $table->id('id_sanksi');
            $table->foreignId('id_user')->constrained('pengguna', 'id_user')->cascadeOnDelete();            // yang dikenai sanksi
            $table->foreignId('id_admin')->nullable()->constrained('pengguna', 'id_user')->nullOnDelete();  // yang memberi sanksi
            $table->foreignId('id_laporan')->nullable()->constrained('laporan_forum', 'id_laporan')->nullOnDelete();
            $table->enum('jenis', ['peringatan', 'mute', 'blokir']);
            $table->string('alasan', 100);                       // Ancaman, Perundungan, Spam, ...
            $table->timestamp('mulai_pada')->nullable()->useCurrent();
            $table->timestamp('berakhir_pada')->nullable();      // null = tanpa batas (peringatan / blokir permanen)
            $table->enum('status', ['aktif', 'dicabut', 'selesai'])->default('aktif');
            $table->timestamp('dicabut_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanksi_pengguna');
    }
};
