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
        Schema::create('daftar_hadir_asesor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_uji_id')->constrained('jadwal_uji')->onDelete('cascade');
            $table->string('no_dokumen')->nullable();
            $table->string('edisi_revisi')->nullable();
            $table->date('tanggal_berlaku')->nullable();
            $table->integer('halaman')->default(1);
            $table->integer('halaman_total')->default(1);
            $table->string('skema')->nullable();
            $table->time('pukul_mulai')->nullable();
            $table->time('pukul_selesai')->nullable();
            $table->date('hari_tanggal')->nullable();
            $table->foreignId('tuk_id')->nullable()->constrained('tuk')->onDelete('set null');
            $table->string('penanggung_jawab_tuk')->nullable();
            $table->json('asesor_signatures')->nullable(); // Menyimpan tanda tangan asesor dalam format JSON
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Asesor yang membuat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_hadir_asesor');
    }
};
