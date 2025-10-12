<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('business_configurations', function (Blueprint $table) {
            $table->string('bus_account_title')->after('bus_logo');
        });
    }

    public function down(): void
    {
        Schema::table('business_configurations', function (Blueprint $table) {
            $table->dropColumn('bus_account_title');
        });
    }
};