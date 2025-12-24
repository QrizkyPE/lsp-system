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
        Schema::create('surat_pernyataan_kesediaan', function (Blueprint $table) {
            $table->id();
            $table->string('no_dokumen')->nullable();
            $table->string('edisi_revisi')->nullable();
            $table->date('tanggal_berlaku')->nullable();
            $table->foreignId('asesor_id')->constrained('asesor')->onDelete('cascade');
            $table->text('alamat')->nullable();
            $table->string('no_met_sertifikat')->nullable();
            $table->foreignId('tuk_id')->nullable()->constrained('tuk')->onDelete('set null');
            $table->enum('status', ['draft', 'sent', 'signed'])->default('draft');
            $table->text('signature_data')->nullable();
            $table->date('tanggal_tanda_tangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_pernyataan_kesediaan');
    }
};
