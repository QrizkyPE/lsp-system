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
        Schema::create('banding_asesmen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_asesi');
            $table->string('nama_asesor');
            $table->date('tanggal_asesmen');
            $table->enum('proses_banding_dijelaskan', ['ya', 'tidak'])->nullable();
            $table->enum('mendiskusikan_banding', ['ya', 'tidak'])->nullable();
            $table->enum('melibatkan_orang_lain', ['ya', 'tidak'])->nullable();
            $table->string('skema_sertifikasi');
            $table->string('nomor_skema');
            $table->text('alasan_banding');
            $table->text('mahasiswa_signature')->nullable();
            $table->date('tanggal_banding');
            $table->foreignId('rekaman_asesmen_id')->constrained('rekaman_asesmen_kompetensi')->onDelete('cascade');
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banding_asesmen');
    }
};
