<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas_tanding', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_usia_id')->constrained('kelompok_usia')->onDelete('cascade');
            $table->enum('jenis_kelamin', ['putra', 'putri']);
            $table->string('kode_kelas'); // Misal: A, B, C, Open
            $table->integer('berat_min');
            $table->integer('berat_max');
            $table->string('label_keterangan')->nullable(); // Misal: "Kelas A Putra (30-33 Kg)"
            $table->boolean('is_open_class')->default(false);
            $table->timestamps(); // Tambahkan jika belum ada
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_tanding');
    }
};