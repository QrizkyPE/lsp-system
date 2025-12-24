<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daftar_hadir_peserta', function (Blueprint $table) {
            $table->string('kepala_tuk')->nullable()->after('penanggung_jawab_tuk');
            $table->string('jumlah_peserta_huruf')->nullable()->after('kepala_tuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_hadir_peserta', function (Blueprint $table) {
            $table->dropColumn(['kepala_tuk', 'jumlah_peserta_huruf']);
        });
    }
};
