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
        Schema::table('laporan_asesmen', function (Blueprint $table) {
            $table->string('tuk_type')->nullable()->after('tuk_id'); // 'sewaktu', 'tempat_kerja', 'mandiri'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_asesmen', function (Blueprint $table) {
            $table->dropColumn('tuk_type');
        });
    }
};
