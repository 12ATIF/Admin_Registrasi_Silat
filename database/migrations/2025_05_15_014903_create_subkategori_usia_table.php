<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subkategori_usia', function (Blueprint $table) {
            $table->id(); // Atau primary key komposit jika lebih disukai
            $table->foreignId('subkategori_id')->constrained('subkategori_lomba')->onDelete('cascade');
            $table->foreignId('kelompok_usia_id')->constrained('kelompok_usia')->onDelete('cascade');
            // $table->timestamps(); // Biasanya tidak perlu timestamps untuk tabel pivot murni
            
            $table->unique(['subkategori_id', 'kelompok_usia_id'], 'subkategori_kelompok_usia_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subkategori_usia');
    }
};