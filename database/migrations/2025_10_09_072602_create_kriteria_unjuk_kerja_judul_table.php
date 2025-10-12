<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriteria_unjuk_kerja_judul', function (Blueprint $table) {
            $table->id();
            $table->string('judul_sertifikasi'); 
            $table->string('kode_unit');
            $table->string('kode_elemen'); 
            $table->string('nomor_kriteria'); 
            $table->text('deskripsi_kriteria'); 
            $table->string('jenis_bukti')->nullable();
            $table->string('metode_asesmen')->nullable();
            $table->string('perangkat_asesmen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriteria_unjuk_kerja_judul');
    }
};