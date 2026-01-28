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
        Schema::create('laporan_asesmen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_uji_id')->constrained('jadwal_uji')->onDelete('cascade');
            $table->foreignId('tuk_id')->nullable()->constrained('tuk')->onDelete('set null');
            $table->foreignId('asesor_id')->constrained('asesor')->onDelete('cascade');
            $table->date('tanggal')->nullable();
            // hasil_asesi disimpan sebagai JSON: {pendaftaran_id: {k: bool, bk: bool, keterangan: string}}
            $table->json('hasil_asesi')->nullable();
            $table->text('aspek_positif_negatif')->nullable();
            $table->text('penolakan_hasil')->nullable();
            $table->text('saran_perbaikan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_asesmen');
    }
};

