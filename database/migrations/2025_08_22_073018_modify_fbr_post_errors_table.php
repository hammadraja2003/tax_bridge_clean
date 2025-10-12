<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fbr_post_errors', function (Blueprint $table) {
            // Increase the length of the 'status' column
            $table->string('status', 50)->change();

            // Optionally, ensure 'error' can hold longer text
            $table->text('error')->change();

            // If invoice_statuses is JSON, make sure the column type is JSON
            $table->json('invoice_statuses')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('fbr_post_errors', function (Blueprint $table) {
            $table->string('status', 20)->change(); // revert back
            $table->string('error', 255)->change(); // revert back
            $table->text('invoice_statuses')->nullable()->change(); // if it was text before
        });
    }
};