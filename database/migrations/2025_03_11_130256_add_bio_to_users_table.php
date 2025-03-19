<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to update the bio field in users table.
 * 
 * Makes the bio field nullable if it exists.
 * This appears to be a corrective migration for an earlier migration.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Updates the bio column in the users table if it exists.
     */
    public function up(): void
    {
        // Only try to modify if column exists
        if (Schema::hasColumn('users', 'bio')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('bio')->nullable()->change(); // Make bio field nullable
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * No reversal needed since we're unsure if the column existed.
     */
    public function down(): void
    {
        // No need to do anything since we're not sure if the column exists
    }
};
