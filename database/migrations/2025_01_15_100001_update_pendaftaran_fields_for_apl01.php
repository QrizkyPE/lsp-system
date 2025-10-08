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
            // Update existing fields and add new ones for FR.APL.01
            $table->string('no_ktp')->nullable();
            $table->string('kebangsaan')->nullable();
            $table->text('alamat_rumah')->nullable();
            $table->string('rumah')->nullable();
            $table->string('kantor')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('kualifikasi_pendidikan')->nullable();
            
            // Data Pekerjaan
            $table->string('nama_institusi')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('alamat_lembaga')->nullable();
            $table->string('kode_pos_lembaga')->nullable();
            $table->string('no_telp_lembaga')->nullable();
            $table->string('no_fax_lembaga')->nullable();
            $table->string('email_lembaga')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropColumn([
                'no_ktp', 'kebangsaan', 'alamat_rumah', 'rumah', 'kantor', 'no_telp',
                'kualifikasi_pendidikan', 'nama_institusi', 'jabatan', 'alamat_lembaga',
                'kode_pos_lembaga', 'no_telp_lembaga', 'no_fax_lembaga', 'email_lembaga'
            ]);
        });
    }
};
