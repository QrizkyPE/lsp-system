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
        Schema::table('observasi_checklists', function (Blueprint $table) {
            $table->text('umpan_balik')->nullable()->after('penilaian_lanjut');
            $table->text('asesor_signature')->nullable()->after('umpan_balik');
            $table->date('tanggal_asesor')->nullable()->after('asesor_signature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('observasi_checklists', function (Blueprint $table) {
            $table->dropColumn(['umpan_balik', 'asesor_signature', 'tanggal_asesor']);
        });
    }
};