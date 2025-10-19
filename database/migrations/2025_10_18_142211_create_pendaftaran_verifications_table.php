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
        Schema::create('pendaftaran_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->foreignId('verifier_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['admin_verification', 'asesor_verification']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('signature_data')->nullable(); // Base64 signature data
            $table->string('verifier_name')->nullable();
            $table->string('verifier_position')->nullable();
            $table->date('verification_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_verifications');
    }
};
