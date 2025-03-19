<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create bio titles table and add relationship to users.
 * 
 * This creates a separate table for user bios and professional titles,
 * then connects it to the users table via a foreign key.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Creates bio titles table and adds FK reference in users table.
     */
    public function up(): void
    {
        // Create table for bio and title information
        Schema::create('fake_bio_titles', function (Blueprint $table) {
            $table->id();                          // Primary key
            $table->string('title');               // User's professional title/headline
            $table->text('bio');                   // User's biography text
            $table->timestamps();                  // created_at and updated_at
        });

        // Add reference from users to bio_titles
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('bio_title_id')
                ->nullable()                     // User can have no bio
                ->constrained('fake_bio_titles') // References fake_bio_titles table
                ->onDelete('set null');          // Set null if bio title deleted
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Removes the FK from users and drops the bio_titles table.
     */
    public function down(): void
    {
        // Remove foreign key and column from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['bio_title_id']);
            $table->dropColumn('bio_title_id');
        });

        // Drop the bio_titles table
        Schema::dropIfExists('fake_bio_titles');
    }
};