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
        Schema::create('merencanakan_aktivitas_dan_proses_asesmen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skema_sertifikasi_id')->constrained('skema_sertifikasi')->onDelete('cascade')->name('mapa_skema_sertifikasi_id_foreign');
            $table->foreignId('asesor_id')->constrained('asesor')->onDelete('cascade')->name('mapa_asesor_id_foreign');
            
            // Section 1.1: Menentukan Pendekatan Asesmen
            $table->string('peserta')->nullable(); // Hasil pelatihan, Pekerja berpengalaman, Pelatihan mandiri
            $table->string('tujuan_asesmen')->nullable(); // Sertifikasi, RCC, RPL, dll
            $table->string('lingkungan')->nullable(); // Tempat kerja nyata, Tempat kerja simulasi
            $table->string('peluang_bukti')->nullable(); // Tersedia, Terbatas
            $table->json('hubungan_standar_kompetensi')->nullable(); // Array dengan emoji ratings
            $table->string('pelaku_asesmen')->nullable(); // Lembaga Sertifikasi, Organisasi Pelatihan, Asesor Perusahaan
            $table->string('konfirmasi_orang_relevan_1')->nullable();
            $table->string('konfirmasi_orang_relevan_1_lainnya')->nullable();
            
            // Section 1.2: Tolok ukur Asesmen
            $table->text('tolok_ukur_asesmen')->nullable();
            
            // Section 2: Mempersiapkan rencana asesmen (stored as JSON per unit kompetensi)
            $table->json('rencana_asesmen')->nullable(); // Array of unit kompetensi with kriteria unjuk kerja
            
            // Section 3: Mengidentifikasi Persyaratan Modifikasi dan Kontekstualisasi
            $table->string('karakteristik_kandidat')->nullable(); // Ada, Tidak Ada
            $table->string('kebutuhan_kontekstualisasi_tempat_kerja')->nullable(); // Ada, Tidak Ada
            $table->string('saran_paket_pelatihan')->nullable(); // Diperlukan, Tidak diperlukan
            $table->string('penyesuaian_perangkat_asesmen')->nullable(); // Ada, Tidak Ada
            $table->string('peluang_kegiatan_terintegrasi')->nullable(); // Ada, Tidak Ada
            $table->json('kegiatan_terintegrasi_units')->nullable(); // Array of unit codes and titles
            $table->string('konfirmasi_orang_relevan_2')->nullable();
            $table->string('konfirmasi_orang_relevan_2_lainnya')->nullable();
            
            // Penyusun dan Validator
            $table->string('penyusun_nama')->nullable();
            $table->string('penyusun_jabatan')->default('Penyusun');
            $table->text('penyusun_tandatangan')->nullable();
            $table->date('penyusun_tanggal')->nullable();
            $table->string('validator_nama')->nullable();
            $table->string('validator_jabatan')->default('Validator');
            $table->text('validator_tandatangan')->nullable();
            $table->date('validator_tanggal')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merencanakan_aktivitas_dan_proses_asesmen');
    }
};
