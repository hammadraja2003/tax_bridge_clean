<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'hash')) {
                $table->string('hash', 64)->nullable()->after('notes')->index();
            }
        });

        Schema::table('invoice_details', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_details', 'hash')) {
                $table->string('hash', 64)->nullable()->after('sro_item_serial_no')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'hash')) {
                $table->dropColumn('hash');
            }
        });

        Schema::table('invoice_details', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_details', 'hash')) {
                $table->dropColumn('hash');
            }
        });
    }
};