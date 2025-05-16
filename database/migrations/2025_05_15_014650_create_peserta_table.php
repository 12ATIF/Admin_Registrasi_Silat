<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontingen_id')->constrained('kontingen')->onDelete('cascade');
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->decimal('berat_badan', 5, 2); // Max 999.99
            $table->foreignId('subkategori_id')->constrained('subkategori_lomba')->onDelete('restrict');
            $table->foreignId('kelompok_usia_id')->constrained('kelompok_usia')->onDelete('restrict');
            $table->foreignId('kelas_tanding_id')->nullable()->constrained('kelas_tanding')->onDelete('set null');
            $table->boolean('is_manual_override')->default(false);
            $table->enum('status_verifikasi', ['pending', 'valid', 'tidak_valid'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};