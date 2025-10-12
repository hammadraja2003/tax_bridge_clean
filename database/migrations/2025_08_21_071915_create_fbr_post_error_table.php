<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fbr_post_errors', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['validation', 'posting'])->comment('validation | posting');
            $table->unsignedSmallInteger('status_code')->nullable()->comment('HTTP status code');
            $table->enum('status', ['success', 'failed'])->nullable();
            $table->string('error_code')->nullable();
            $table->longText('error')->nullable();
            $table->json('invoice_statuses')->nullable(); // array save karne k liye
            $table->json('raw_response')->nullable();    // pura json store for debugging
            $table->timestamp('error_time')->useCurrent();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('type');
            $table->index('status_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fbr_post_errors');
    }
};