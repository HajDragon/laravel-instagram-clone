<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create the followers table for the social graph.
 * 
 * Creates a table to track follower relationships between users,
 * similar to Instagram's follow system.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Creates the followers table with user relationships.
     */
    public function up(): void
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->id();                              // Primary key

            // User who is following someone
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');              // Delete follow when user is deleted

            // User who is being followed
            $table->foreignId('following_id')
                ->constrained('users')
                ->onDelete('cascade');              // Delete follow when followed user is deleted

            $table->timestamps();                     // created_at and updated_at

            // Prevent duplicate follows
            $table->unique(['user_id', 'following_id']);
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Drops the followers table.
     */
    public function down(): void
    {
        Schema::dropIfExists('followers');
    }
};