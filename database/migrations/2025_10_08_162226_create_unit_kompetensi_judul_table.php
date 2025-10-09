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
        Schema::create('unit_kompetensi_judul', function (Blueprint $table) {
            $table->id();
            $table->string('judul_sertifikasi');
            $table->string('kode_unit');
            $table->string('judul_unit');
            $table->string('standar_kompetensi_kerja');
            $table->timestamps();
            
            $table->index('judul_sertifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_kompetensi_judul');
    }
};