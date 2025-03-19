<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to add profile image support to users.
 * 
 * Adds a column to store the path to the user's profile image,
 * allowing users to customize their profile picture.
 */
return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Adds profile_image column to the users table.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_image')->nullable(); // Path to profile image, optional
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Removes the profile_image column from users.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_image');
        });
    }
};
