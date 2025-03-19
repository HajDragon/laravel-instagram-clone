<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create cache-related tables for Laravel's caching system.
 * 
 * Creates two tables:
 * - cache: For general caching of data
 * - cache_locks: For managing distributed locks during cache operations
 */
return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Creates the cache tables used by Laravel's cache system.
     */
    public function up(): void
    {
        // Main cache table for storing cached items
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();        // Cache key
            $table->mediumText('value');             // Cached value (serialized)
            $table->integer('expiration');           // Expiration timestamp
        });

        // Cache locks table for atomic/distributed operations
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();        // Lock key
            $table->string('owner');                 // Process that owns the lock
            $table->integer('expiration');           // Lock expiration timestamp
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Removes the cache tables.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
