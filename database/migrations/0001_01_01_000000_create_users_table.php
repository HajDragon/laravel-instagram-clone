<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Initial migration that creates the core authentication tables.
 * 
 * This migration creates:
 * - users: The main user accounts table
 * - password_reset_tokens: For handling password resets
 * - sessions: For managing user sessions
 */
return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Creates the primary authentication tables needed for the application.
     */
    public function up(): void
    {
        // Create the main users table with basic auth fields
        Schema::create('users', function (Blueprint $table) {
            $table->id();                              // Primary key
            $table->string('name');                    // User's display name
            $table->string('email')->unique();         // Unique email for login
            $table->timestamp('email_verified_at')->nullable(); // When email was verified
            $table->string('password');                // Hashed password
            $table->rememberToken();                   // For "remember me" token
            $table->timestamps();                      // created_at and updated_at
        });

        // Table for storing password reset tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();        // Email as primary key
            $table->string('token');                   // Reset token
            $table->timestamp('created_at')->nullable(); // When token was created
        });

        // Table for storing user sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();           // Session ID
            $table->foreignId('user_id')->nullable()->index(); // Associated user
            $table->string('ip_address', 45)->nullable(); // User's IP
            $table->text('user_agent')->nullable();    // Browser/device info
            $table->longText('payload');               // Session data
            $table->integer('last_activity')->index(); // Last activity timestamp
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drops all tables created by this migration in reverse order.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
