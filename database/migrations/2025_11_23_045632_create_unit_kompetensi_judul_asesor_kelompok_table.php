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
        Schema::create('unit_kompetensi_judul_asesor_kelompok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_kompetensi_judul_id')->constrained('unit_kompetensi_judul')->onDelete('cascade')->name('ukj_asesor_kelompok_unit_id_foreign');
            $table->foreignId('asesor_id')->constrained('asesor')->onDelete('cascade')->name('ukj_asesor_kelompok_asesor_id_foreign');
            $table->integer('kelompok'); // 1, 2, atau 3
            $table->timestamps();
            
            // Unique constraint: satu asesor tidak bisa di kelompok yang sama untuk unit kompetensi yang sama
            $table->unique(['unit_kompetensi_judul_id', 'asesor_id', 'kelompok'], 'unique_unit_asesor_kelompok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_kompetensi_judul_asesor_kelompok');
    }
};
