<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aturan_usia', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->foreignId('kelompok_usia_id')->constrained('kelompok_usia')->onDelete('cascade');
            $table->integer('usia_min');
            $table->integer('usia_max');
            $table->timestamps(); // Tambahkan
            
            $table->unique(['tahun', 'kelompok_usia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_usia');
    }
};