<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only try to modify if column exists
        if (Schema::hasColumn('users', 'bio')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('bio')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to do anything since we're not sure if the column exists
    }
};
