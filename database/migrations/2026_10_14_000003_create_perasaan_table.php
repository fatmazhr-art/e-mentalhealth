<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Perasaan', function (Blueprint $table) {
            $table->id('id_perasaan');
            $table->string('nama', 50);
            $table->enum('kategori', ['positif', 'negatif', 'netral']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Perasaan');
    }
};
