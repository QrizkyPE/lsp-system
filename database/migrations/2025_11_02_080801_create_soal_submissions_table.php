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
        Schema::create('soal_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_upload_id')->constrained('soal_uploads')->onDelete('cascade');
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('file_type')->nullable(); // word, pdf
            $table->integer('file_size')->nullable(); // in bytes
            $table->timestamp('submitted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_submissions');
    }
};
