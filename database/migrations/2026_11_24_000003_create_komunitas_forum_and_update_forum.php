<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komunitas_forum', function (Blueprint $table) {
            $table->id('id_komunitas');
            $table->string('nama', 100)->unique();
        });

        Schema::table('forum_diskusi_anonim', function (Blueprint $table) {
            $table->foreignId('id_komunitas')->nullable()->after('id_induk')
                ->constrained('komunitas_forum', 'id_komunitas')->nullOnDelete();
            // Status moderasi. "Dilaporkan N" dihitung dari tabel laporan_forum.
            $table->enum('status', ['aktif', 'berisiko', 'dihapus'])->default('aktif');
            $table->timestamp('dihapus_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('forum_diskusi_anonim', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_komunitas');
            $table->dropColumn(['status', 'dihapus_pada']);
        });
        Schema::dropIfExists('komunitas_forum');
    }
};
