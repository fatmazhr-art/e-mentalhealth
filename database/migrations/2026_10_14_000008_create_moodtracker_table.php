<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Moodtracker', function (Blueprint $table) {
            $table->id('id_mt');
            $table->foreignId('id_user')->constrained('Pengguna', 'id_user')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_perasaan')->constrained('Perasaan', 'id_perasaan')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->text('journal')->nullable();
            $table->date('tanggal'); // diisi aplikasi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Moodtracker');
    }
};
