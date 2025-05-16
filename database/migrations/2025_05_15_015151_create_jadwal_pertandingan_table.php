<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pertandingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertandingan_id')->constrained('pertandingan')->onDelete('cascade');
            $table->foreignId('subkategori_id')->constrained('subkategori_lomba')->onDelete('cascade');
            $table->foreignId('kelompok_usia_id')->constrained('kelompok_usia')->onDelete('cascade');
            // $table->foreignId('kelas_tanding_id')->nullable()->constrained('kelas_tanding')->onDelete('set null'); // Jika jadwal spesifik per kelas tanding
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->string('lokasi_detail')->nullable(); // Misal: Gelanggang A, Matras 1
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pertandingan');
    }
};