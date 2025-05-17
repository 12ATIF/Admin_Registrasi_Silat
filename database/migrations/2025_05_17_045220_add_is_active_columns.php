<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'is_active' column to pelatih table if it doesn't exist
        if (!Schema::hasColumn('pelatih', 'is_active')) {
            Schema::table('pelatih', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }
        
        // Add 'is_active' column to kontingen table if it doesn't exist
        if (!Schema::hasColumn('kontingen', 'is_active')) {
            Schema::table('kontingen', function (Blueprint $table) {
                $table->boolean('is_active')->default(true);
            });
        }
    }

    public function down(): void
    {
        // Remove 'is_active' column from pelatih table if it exists
        if (Schema::hasColumn('pelatih', 'is_active')) {
            Schema::table('pelatih', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
        
        // Remove 'is_active' column from kontingen table if it exists
        if (Schema::hasColumn('kontingen', 'is_active')) {
            Schema::table('kontingen', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
