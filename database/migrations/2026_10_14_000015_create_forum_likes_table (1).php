<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_likes', function (Blueprint $table) {
            $table->id('id_like');
            $table->foreignId('id_post')->constrained('forum_diskusi_anonim', 'id_post')->cascadeOnDelete();
            $table->foreignId('id_user')->constrained('pengguna', 'id_user')->cascadeOnDelete();
            $table->timestamp('dibuat_pada')->nullable()->useCurrent();

            $table->unique(['id_post', 'id_user']); // satu user hanya bisa like sekali
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_likes');
    }
};
