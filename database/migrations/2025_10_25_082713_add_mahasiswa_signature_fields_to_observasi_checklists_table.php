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
            $table->text('mahasiswa_signature')->nullable()->after('tanggal_asesor');
            $table->date('tanggal_mahasiswa')->nullable()->after('mahasiswa_signature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('observasi_checklists', function (Blueprint $table) {
            $table->dropColumn(['mahasiswa_signature', 'tanggal_mahasiswa']);
        });
    }
};