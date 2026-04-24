<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soundings', function (Blueprint $table) {
            $table->string('petugas_sounding')->nullable()->after('jam_pemeriksaan');
        });
    }

    public function down(): void
    {
        Schema::table('soundings', function (Blueprint $table) {
            $table->dropColumn('petugas_sounding');
        });
    }
};