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
        Schema::create('business_configurations', function (Blueprint $table) {
            $table->bigIncrements('bus_config_id');
            $table->string('bus_name');
            $table->string('bus_ntn_cnic');
            $table->text('bus_address');
            $table->string('bus_logo');
            $table->string('bus_account_number');
            $table->string('bus_reg_num');
            $table->string('bus_contact_num', 20);
            $table->string('bus_contact_person');
            $table->string('bus_IBAN');
            $table->string('bus_swift_code')->nullable();
            $table->string('bus_acc_branch_name');
            $table->string('bus_acc_branch_code')->nullable();
            $table->timestamps(); // creates `created_at` and `updated_at` (nullable by default)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_configurations');
    }
};
