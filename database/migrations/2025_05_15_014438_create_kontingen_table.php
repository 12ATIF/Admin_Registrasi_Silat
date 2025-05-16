<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kontingen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelatih_id')->constrained('pelatih')->onDelete('cascade');
            $table->string('nama');
            $table->string('asal_daerah');
            $table->string('kontak_pendamping')->nullable();
            $table->boolean('is_active')->default(true); // Untuk fitur nonaktifkan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontingen');
    }
};