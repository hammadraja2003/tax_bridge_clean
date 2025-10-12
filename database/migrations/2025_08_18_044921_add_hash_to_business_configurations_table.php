<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('business_configurations', function (Blueprint $table) {
            $table->string('hash')->nullable()->after('bus_acc_branch_code');
        });
    }

    public function down()
    {
        Schema::table('business_configurations', function (Blueprint $table) {
            $table->dropColumn('hash');
        });
    }
};