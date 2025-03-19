<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the comments table.
 * 
 * Creates a table to store user comments on posts,
 * with relationships to both users and posts.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Creates the comments table with user and post relationships.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();                             // Primary key
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');              // Comment author, cascade on user delete
            $table->foreignId('post_id')
                  ->constrained()
                  ->onDelete('cascade');              // Related post, cascade on post delete
            $table->text('content');                  // Comment text content
            $table->timestamps();                     // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drops the comments table.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
