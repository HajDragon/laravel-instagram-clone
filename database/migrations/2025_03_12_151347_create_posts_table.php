<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the posts table.
 * 
 * Creates the main posts table for the Instagram clone,
 * where user photo posts and their metadata are stored.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Creates the posts table if it doesn't exist already.
     */
    public function up(): void
    {
        // Only create if table doesn't exist
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();                         // Primary key
                $table->foreignId('user_id')
                    ->constrained()
                    ->onDelete('cascade');          // Post creator, delete posts when user deleted
                $table->string('image_path');         // Path to image file or Picsum reference
                $table->text('caption')->nullable();  // Optional post caption
                $table->string('location')->nullable(); // Optional location tag
                $table->timestamps();                 // created_at and updated_at
            });
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Drops the posts table.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
