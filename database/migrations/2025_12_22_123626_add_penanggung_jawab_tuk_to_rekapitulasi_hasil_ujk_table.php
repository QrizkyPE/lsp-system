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
        Schema::table('rekapitulasi_hasil_ujk', function (Blueprint $table) {
            $table->string('penanggung_jawab_tuk')->nullable()->after('tuk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekapitulasi_hasil_ujk', function (Blueprint $table) {
            $table->dropColumn('penanggung_jawab_tuk');
        });
    }
};
