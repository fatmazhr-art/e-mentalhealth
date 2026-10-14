<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Pengguna', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama', 100);
            $table->string('nama_anonim', 50)->nullable()->unique();
            $table->enum('role', ['admin', 'user', 'konselor'])->default('user');
            $table->string('prodi', 100)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->string('nim', 20)->nullable()->unique();
            $table->string('email', 100)->unique();
            $table->string('kata_sandi', 255); // simpan dalam bentuk hash
            $table->string('kode_otp', 255)->nullable(); // hash OTP lupa kata sandi
            $table->dateTime('otp_kedaluwarsa')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Pengguna');
    }
};
