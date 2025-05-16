<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null'); // Set null jika admin dihapus
            $table->string('aksi'); // Misal: created, updated, deleted, verified_payment
            $table->string('model')->nullable(); // Misal: App\Models\Peserta
            $table->unsignedBigInteger('model_id')->nullable(); // ID dari record yang terpengaruh
            // $table->text('perubahan')->nullable(); // JSON dari perubahan data (opsional)
            $table->timestamp('waktu_aksi')->useCurrent();
            // Tidak perlu timestamps() standar jika sudah ada waktu_aksi
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};