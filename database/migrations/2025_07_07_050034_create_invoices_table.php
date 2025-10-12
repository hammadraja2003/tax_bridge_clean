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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->string('invoice_type', 50);
            $table->date('invoice_date');
            $table->string('scenario_id', 50)->nullable();
            $table->string('invoice_ref_no', 100)->nullable();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('buyer_id');
            $table->string('fbr_invoice_number', 255)->nullable();
            $table->tinyInteger('is_posted_to_fbr')->default(0);
            $table->string('response_status', 50)->nullable();
            $table->text('response_message')->nullable();
            $table->timestamps();

            $table->foreign('seller_id')->references('bus_config_id')->on('business_configurations')->onDelete('cascade');
            $table->foreign('buyer_id')->references('byr_id')->on('buyers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};