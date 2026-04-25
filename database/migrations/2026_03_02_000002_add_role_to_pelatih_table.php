<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pelatih', 'role')) {
            Schema::table('pelatih', function (Blueprint $table) {
                $table->string('role')->default('user')->after('is_active');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pelatih', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
