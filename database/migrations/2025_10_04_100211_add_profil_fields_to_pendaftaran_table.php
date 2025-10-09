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
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Data Pribadi
            $table->string('nama_lengkap')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('agama')->nullable();
            
            // Data Kontak
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();
            
            // Data Alamat
            $table->text('alamat_lengkap')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            
            // Data Pendidikan
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('institusi_pendidikan')->nullable();
            
            // Data Pekerjaan
            $table->string('pekerjaan')->nullable();
            $table->string('instansi_pekerjaan')->nullable();
            
            // Data Anggaran
            $table->string('sumber_anggaran')->nullable();
            $table->string('pemberi_anggaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'kewarganegaraan', 'agama', 'no_telepon', 'email',
                'alamat_lengkap', 'kota', 'provinsi', 'kode_pos',
                'pendidikan_terakhir', 'institusi_pendidikan',
                'pekerjaan', 'instansi_pekerjaan',
                'sumber_anggaran', 'pemberi_anggaran'
            ]);
        });
    }
};
