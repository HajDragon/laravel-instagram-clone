<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the personal access tokens table.
 * 
 * Creates a table for API token storage, used by Laravel Sanctum
 * for API authentication.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Creates the personal_access_tokens table.
     */
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();                             // Primary key
            $table->morphs('tokenable');              // Dynamic relationship to any model
            $table->string('name');                   // Token name/purpose
            $table->string('token', 64)->unique();    // Hashed token value
            $table->text('abilities')->nullable();    // Token permissions
            $table->timestamp('last_used_at')->nullable(); // Last token usage
            $table->timestamp('expires_at')->nullable(); // Token expiration date
            $table->timestamps();                     // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drops the personal_access_tokens table.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
