<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('id_log');
            $table->string('actor', 100);
            $table->string('action', 50);
            $table->string('object', 100);
            $table->dateTime('timestamp')->nullable()->useCurrent();
            $table->string('result', 20);
            $table->text('details')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
