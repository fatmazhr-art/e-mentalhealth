<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perasaan', function (Blueprint $table) {
            $table->id('id_perasaan');
            $table->string('nama', 50);
            $table->string('kategori', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perasaan');
    }
};
