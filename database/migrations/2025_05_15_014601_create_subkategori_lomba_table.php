<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subkategori_lomba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_lomba')->onDelete('cascade');
            $table->string('nama'); // Misal: Tunggal Putra, Ganda Putri, Kelas A Putra
            $table->enum('jenis', ['tunggal', 'ganda', 'regu', 'tanding']);
            $table->integer('jumlah_peserta')->default(1); // Untuk ganda=2, regu=3, tanding/tunggal=1
            $table->decimal('harga_pendaftaran', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subkategori_lomba');
    }
};