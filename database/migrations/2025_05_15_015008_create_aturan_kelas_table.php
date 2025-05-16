<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aturan_kelas', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->foreignId('kelompok_usia_id')->constrained('kelompok_usia')->onDelete('cascade');
            $table->enum('jenis_kelamin', ['putra', 'putri']);
            $table->string('kode_kelas');
            $table->integer('berat_min');
            $table->integer('berat_max');
            $table->timestamps(); // Tambahkan

            $table->unique(['tahun', 'kelompok_usia_id', 'jenis_kelamin', 'kode_kelas'], 'aturan_kelas_unique_rule');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_kelas');
    }
};