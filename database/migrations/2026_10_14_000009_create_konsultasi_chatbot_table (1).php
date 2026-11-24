<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasi_chatbot', function (Blueprint $table) {
            $table->id('id_chatbot');
            $table->foreignId('id_user')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->text('pesan');
            $table->enum('pengirim', ['user', 'bot']);
            $table->timestamp('waktu')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasi_chatbot');
    }
};
