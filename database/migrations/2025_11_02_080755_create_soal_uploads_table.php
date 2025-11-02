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
        Schema::create('soal_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_uji_id')->constrained('jadwal_uji')->onDelete('cascade');
            $table->foreignId('asesor_id')->constrained('asesor')->onDelete('cascade');
            $table->string('jenis_instrumen'); // FR.IA.02, FR.IA.03, etc.
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('file_type')->nullable(); // word, pdf
            $table->integer('file_size')->nullable(); // in bytes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_uploads');
    }
};
