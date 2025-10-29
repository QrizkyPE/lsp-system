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
        Schema::create('rekaman_asesmen_kompetensi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nomor_skema');
            $table->enum('tuk', ['sewaktu', 'tempat_kerja', 'mandiri']);
            $table->string('nama_asesor');
            $table->string('nama_asesi');
            $table->date('tanggal_mulai');
            $table->time('waktu_mulai');
            $table->date('tanggal_selesai');
            $table->time('waktu_selesai');
            $table->json('unit_kompetensi_data')->nullable();
            $table->enum('rekomendasi_hasil', ['kompeten', 'belum_kompeten'])->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('komentar_observasi')->nullable();
            $table->string('no_reg_asesor')->nullable();
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
        Schema::dropIfExists('rekaman_asesmen_kompetensi');
    }
};
