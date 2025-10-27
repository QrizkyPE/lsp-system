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
        Schema::create('penyesuaian_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nomor_skema');
            $table->enum('tuk', ['sewaktu', 'tempat_kerja', 'mandiri']);
            $table->string('nama_asesor');
            $table->string('nama_asesi');
            $table->date('tanggal');
            $table->json('potensi_asesi')->nullable();
            $table->json('modifikasi_data')->nullable();
            $table->string('acuan_pembanding')->nullable();
            $table->string('metode_asesmen')->nullable();
            $table->string('instrumen_asesmen')->nullable();
            $table->text('asesor_signature')->nullable();
            $table->date('tanggal_asesor')->nullable();
            $table->text('mahasiswa_signature')->nullable();
            $table->date('tanggal_mahasiswa')->nullable();
            $table->foreignId('asesor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyesuaian_checklists');
    }
};
