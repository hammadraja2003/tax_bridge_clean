<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->tinyInteger('invoice_status')->default(1)->after('is_posted_to_fbr'); // 1 = draft
            $table->float('shipping_charges')->nullable()->after('totalextraTax');
            $table->float('other_charges')->nullable()->after('shipping_charges');
            $table->float('discount_amount')->nullable()->after('other_charges');
            $table->string('payment_status', 50)->nullable()->after('discount_amount'); // e.g., Paid, Unpaid
            $table->text('notes')->nullable()->after('payment_status');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_status',
                'shipping_charges',
                'other_charges',
                'discount_amount',
                'payment_status',
                'notes',
            ]);
        });
    }
}