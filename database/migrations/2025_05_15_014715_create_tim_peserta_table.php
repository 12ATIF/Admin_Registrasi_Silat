<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tim_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subkategori_id')->constrained('subkategori_lomba')->onDelete('cascade'); // Pastikan subkategori jenis ganda/regu
            $table->foreignId('kelompok_usia_id')->constrained('kelompok_usia')->onDelete('cascade');
            $table->foreignId('kontingen_id')->constrained('kontingen')->onDelete('cascade');
            $table->string('nama_tim');
            $table->timestamps(); // Tambahkan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim_peserta');
    }
};