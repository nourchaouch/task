<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            if (!Schema::hasColumn('users', 'role')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('role')->default('team_member')->after('password');
                });
            }
        } catch (\Exception $e) {
            // Log the error but don't throw it
            \Log::error('Migration error: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            if (Schema::hasColumn('users', 'role')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropColumn('role');
                });
            }
        } catch (\Exception $e) {
            \Log::error('Migration rollback error: ' . $e->getMessage());
        }
    }
};
