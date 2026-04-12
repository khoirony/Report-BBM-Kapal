<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagu_anggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ukpd_id')->constrained('ukpds')->onDelete('cascade');
            $table->year('tahun');
            $table->decimal('nominal', 20, 2);
            $table->timestamps();
            
            $table->unique(['ukpd_id', 'tahun']); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagu_anggarans');
    }
};