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
        Schema::table('unit_kompetensi_judul', function (Blueprint $table) {
            $table->boolean('ada_pembagian_kelompok')->default(false)->after('status');
            $table->integer('jumlah_kelompok')->nullable()->after('ada_pembagian_kelompok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_kompetensi_judul', function (Blueprint $table) {
            $table->dropColumn(['ada_pembagian_kelompok', 'jumlah_kelompok']);
        });
    }
};
