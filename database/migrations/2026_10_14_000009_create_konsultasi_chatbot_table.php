<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Konsultasi_Chatbot', function (Blueprint $table) {
            $table->id('id_chatbot');
            $table->foreignId('id_user')->constrained('Pengguna', 'id_user')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('pesan');
            $table->enum('pengirim', ['user', 'bot']);
            $table->dateTime('waktu')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Konsultasi_Chatbot');
    }
};
