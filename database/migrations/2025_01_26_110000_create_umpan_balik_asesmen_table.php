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
        Schema::create('umpan_balik_asesmen', function (Blueprint $table) {
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
            $table->json('umpan_balik_data');
            $table->text('catatan_lainnya')->nullable();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rekaman_asesmen_id')->constrained('rekaman_asesmen_kompetensi')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umpan_balik_asesmen');
    }
};
