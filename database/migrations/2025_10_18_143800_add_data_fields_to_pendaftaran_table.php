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
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->json('profil_data')->nullable()->after('tanggal_pendaftaran');
            $table->json('sertifikasi_data')->nullable()->after('profil_data');
            $table->json('asesmen_data')->nullable()->after('sertifikasi_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn(['profil_data', 'sertifikasi_data', 'asesmen_data']);
        });
    }
};
