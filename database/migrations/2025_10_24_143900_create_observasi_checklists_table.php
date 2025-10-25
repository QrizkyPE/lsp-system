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
        Schema::create('observasi_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nomor_skema');
            $table->enum('tuk', ['sewaktu', 'tempat_kerja', 'mandiri']);
            $table->string('nama_asesor');
            $table->string('nama_asesi');
            $table->date('tanggal');
            $table->json('elemen_data'); // Data elemen dan kriteria unjuk kerja
            $table->json('observasi_data')->nullable(); // Data hasil observasi
            $table->text('istilah_acuan')->nullable(); // SOP/spesifikasi produk
            $table->text('penilaian_lanjut')->nullable(); // Penilaian lanjut jika diperlukan
            $table->unsignedBigInteger('asesor_id'); // ID asesor yang membuat
            $table->unsignedBigInteger('pendaftaran_id'); // ID pendaftaran mahasiswa
            $table->timestamps();
            
            $table->foreign('asesor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pendaftaran_id')->references('id')->on('pendaftaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observasi_checklists');
    }
};
