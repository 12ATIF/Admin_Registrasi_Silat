<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelompok_usia', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique(); // Misal: Usia Dini, Pra Remaja
            $table->integer('rentang_usia_min');
            $table->integer('rentang_usia_max');
            $table->timestamps(); // Tambahkan jika belum ada
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelompok_usia');
    }
};