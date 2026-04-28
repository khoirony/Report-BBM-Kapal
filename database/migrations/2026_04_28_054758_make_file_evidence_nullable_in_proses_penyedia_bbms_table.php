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
        Schema::table('proses_penyedia_bbms', function (Blueprint $table) {
            $table->string('file_evidence')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proses_penyedia_bbms', function (Blueprint $table) {
            $table->string('file_evidence')->nullable(false)->change();
        });
    }
};
