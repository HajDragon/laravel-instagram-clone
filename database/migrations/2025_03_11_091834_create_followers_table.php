<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('following_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'following_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('followers');
    }
};