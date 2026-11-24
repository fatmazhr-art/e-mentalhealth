<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rehat', function (Blueprint $table) {
            $table->string('judul', 200)->after('id_user');
            $table->text('deskripsi')->nullable()->after('judul');
            $table->string('kreator', 100)->nullable()->after('deskripsi');          // penulis artikel / pengisi suara
            $table->enum('status', ['draf', 'dipublikasi'])->default('draf')->after('jenis_konten');
            $table->string('gambar', 255)->nullable();                                 // thumbnail
            $table->string('url_sumber', 500)->nullable();                             // link artikel
            $table->string('media_url', 500)->nullable();                              // file/link audio & video
            $table->unsignedInteger('durasi_detik')->nullable();
            $table->unsignedInteger('jumlah_dilihat')->default(0);                     // "x baca" / "x diputar"
            $table->string('warna', 7)->nullable();                                    // warna kartu audio, mis. #4F4F4F
            $table->timestamp('dibuat_pada')->nullable()->useCurrent();

            // Kategori sekarang banyak-ke-banyak lewat tabel rehat_kategori
            $table->dropColumn('kategori');
            // Artikel hanya berupa link, jadi isi tidak wajib
            $table->text('isi')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rehat', function (Blueprint $table) {
            $table->string('kategori', 50)->default('');
            $table->dropColumn([
                'judul', 'deskripsi', 'kreator', 'status', 'gambar', 'url_sumber',
                'media_url', 'durasi_detik', 'jumlah_dilihat', 'warna', 'dibuat_pada',
            ]);
        });
    }
};
