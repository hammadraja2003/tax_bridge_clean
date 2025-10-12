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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id('invoice_detail_id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->integer('quantity');
            $table->decimal('total_value', 12, 2);
            $table->decimal('value_excl_tax', 12, 2);
            $table->decimal('retail_price', 10, 2)->nullable();
            $table->decimal('sales_tax_applicable', 12, 2);
            $table->decimal('sales_tax_withheld', 12, 2)->default(0);
            $table->decimal('extra_tax', 12, 2)->default(0);
            $table->decimal('further_tax', 12, 2)->default(0);
            $table->decimal('fed_payable', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->string('sale_type', 255);
            $table->string('sro_schedule_no', 50)->nullable();
            $table->string('sro_item_serial_no', 50)->nullable();
            $table->timestamps();

            $table->foreign('invoice_id')->references('invoice_id')->on('invoices')->onDelete('cascade');
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_details');
    }
};