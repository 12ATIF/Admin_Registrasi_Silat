<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tim_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_id')->constrained('tim_peserta')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('peserta')->onDelete('cascade');
            $table->timestamps(); // Opsional, tapi bisa berguna

            $table->unique(['tim_id', 'peserta_id']); // Peserta hanya bisa jadi anggota satu tim sekali
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tim_anggota');
    }
};