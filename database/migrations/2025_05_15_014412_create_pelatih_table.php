<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelatih', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('perguruan');
            $table->string('no_hp')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(true); // Untuk fitur nonaktifkan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatih');
    }
};