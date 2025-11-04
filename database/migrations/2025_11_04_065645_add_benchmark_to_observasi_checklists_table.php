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
        Schema::table('observasi_checklists', function (Blueprint $table) {
            if (!Schema::hasColumn('observasi_checklists', 'benchmark')) {
                $table->json('benchmark')->nullable()->after('istilah_acuan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('observasi_checklists', function (Blueprint $table) {
            if (Schema::hasColumn('observasi_checklists', 'benchmark')) {
                $table->dropColumn('benchmark');
            }
        });
    }
};
