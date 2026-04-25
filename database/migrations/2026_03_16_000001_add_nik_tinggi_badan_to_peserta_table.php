<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            if (!Schema::hasColumn('peserta', 'nik')) {
                $table->string('nik', 16)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('peserta', 'tinggi_badan')) {
                $table->decimal('tinggi_badan', 5, 2)->nullable()->after('berat_badan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            $table->dropColumn(['nik', 'tinggi_badan']);
        });
    }
};
