<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create queue-related tables for Laravel's job system.
 * 
 * Creates three tables:
 * - jobs: For storing queued jobs
 * - job_batches: For handling job batches
 * - failed_jobs: For tracking failed jobs
 */
return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Sets up tables needed for Laravel's queue system.
     */
    public function up(): void
    {
        // Main jobs table for queue processing
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();                             // Job ID
            $table->string('queue')->index();         // Queue name
            $table->longText('payload');              // Serialized job data
            $table->unsignedTinyInteger('attempts');  // How many times job was attempted
            $table->unsignedInteger('reserved_at')->nullable(); // When job was reserved
            $table->unsignedInteger('available_at');  // When job becomes available
            $table->unsignedInteger('created_at');    // When job was created
        });

        // Table for tracking batches of jobs
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();          // Batch ID
            $table->string('name');                   // Batch name
            $table->integer('total_jobs');            // Total jobs in batch
            $table->integer('pending_jobs');          // Pending jobs count
            $table->integer('failed_jobs');           // Failed jobs count
            $table->longText('failed_job_ids');       // IDs of failed jobs
            $table->mediumText('options')->nullable(); // Batch options
            $table->integer('cancelled_at')->nullable(); // When batch was cancelled
            $table->integer('created_at');            // When batch was created
            $table->integer('finished_at')->nullable(); // When batch finished
        });

        // Table for tracking failed jobs
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();                             // Failed job ID
            $table->string('uuid')->unique();         // Unique identifier
            $table->text('connection');               // Queue connection
            $table->text('queue');                    // Queue name
            $table->longText('payload');              // Serialized job data
            $table->longText('exception');            // Error details
            $table->timestamp('failed_at')->useCurrent(); // When job failed
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Removes all job-related tables.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
