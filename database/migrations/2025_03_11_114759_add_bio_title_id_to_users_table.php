<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fake_bio_titles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('bio');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('bio_title_id')->nullable()
                ->constrained('fake_bio_titles')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['bio_title_id']);
            $table->dropColumn('bio_title_id');
        });

        Schema::dropIfExists('fake_bio_titles');
    }
};