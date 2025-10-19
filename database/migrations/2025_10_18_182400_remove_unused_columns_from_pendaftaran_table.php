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
            // Hapus kolom yang tidak diperlukan karena data sudah disimpan dalam JSON
            $table->dropColumn([
                // Data Pribadi (dari migration 2025_10_04_100211)
                'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'kewarganegaraan', 'agama', 'no_telepon', 'email',
                'alamat_lengkap', 'kota', 'provinsi', 'kode_pos',
                'pendidikan_terakhir', 'institusi_pendidikan',
                'pekerjaan', 'instansi_pekerjaan',
                'sumber_anggaran', 'pemberi_anggaran',
                
                // Data APL.01 (dari migration 2025_10_04_100212)
                'no_ktp', 'kebangsaan', 'alamat_rumah', 'rumah', 'kantor', 'no_telp',
                'kualifikasi_pendidikan', 'nama_institusi', 'jabatan', 'alamat_lembaga',
                'kode_pos_lembaga', 'no_telp_lembaga', 'no_fax_lembaga', 'email_lembaga'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            // Restore columns if needed
            $table->string('nama_lengkap')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('agama')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('institusi_pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('instansi_pekerjaan')->nullable();
            $table->string('sumber_anggaran')->nullable();
            $table->string('pemberi_anggaran')->nullable();
            $table->string('no_ktp')->nullable();
            $table->string('kebangsaan')->nullable();
            $table->text('alamat_rumah')->nullable();
            $table->string('rumah')->nullable();
            $table->string('kantor')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('kualifikasi_pendidikan')->nullable();
            $table->string('nama_institusi')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('alamat_lembaga')->nullable();
            $table->string('kode_pos_lembaga')->nullable();
            $table->string('no_telp_lembaga')->nullable();
            $table->string('no_fax_lembaga')->nullable();
            $table->string('email_lembaga')->nullable();
        });
    }
};
