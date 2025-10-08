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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('skema_sertifikasi_id')->constrained('skema_sertifikasi')->onDelete('cascade');
            $table->foreignId('jadwal_uji_id')->constrained('jadwal_uji')->onDelete('cascade');
            $table->string('no_pendaftaran')->unique();
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamp('tanggal_pendaftaran');
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamp('tanggal_asesmen')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->enum('hasil_asesmen', ['kompeten', 'belum_kompeten'])->nullable();
            $table->text('catatan_asesmen')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
