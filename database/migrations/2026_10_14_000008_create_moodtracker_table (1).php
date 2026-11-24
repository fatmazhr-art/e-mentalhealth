<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moodtracker', function (Blueprint $table) {
            $table->id('id_mt');
            $table->foreignId('id_user')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->foreignId('id_perasaan')->constrained('perasaan', 'id_perasaan')->cascadeOnDelete();
            // Jurnal disimpan terenkripsi (cast 'encrypted' di model MoodTracker).
            // Hasil enkripsi lebih panjang dari teks asli, jadi pakai longText.
            $table->longText('journal_cipher')->nullable();
            $table->date('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moodtracker');
    }
};
