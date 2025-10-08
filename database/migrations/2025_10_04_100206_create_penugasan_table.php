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
        Schema::create('penugasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_uji_id')->constrained('jadwal_uji')->onDelete('cascade');
            $table->foreignId('asesor_id')->constrained('asesor')->onDelete('cascade');
            $table->enum('jenis_penugasan', ['asesor', 'mapa', 'ma', 'mkva']);
            $table->enum('status', ['assigned', 'accepted', 'rejected', 'completed'])->default('assigned');
            $table->text('keterangan')->nullable();
            $table->timestamp('tanggal_penugasan');
            $table->timestamp('tanggal_konfirmasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan');
    }
};
