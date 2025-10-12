<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elemen_judul', function (Blueprint $table) {
            $table->id();
            $table->string('judul_sertifikasi'); 
            $table->string('kode_unit'); 
            $table->string('kode_elemen'); 
            $table->string('nama_elemen'); 
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elemen_judul');
    }
};