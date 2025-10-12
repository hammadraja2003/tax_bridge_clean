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
        Schema::create('buyers', function (Blueprint $table) {
            $table->bigIncrements('byr_id');
            $table->string('byr_name');
            $table->unsignedTinyInteger('byr_type')->default(0); // 0 = Individual, 1 = Company (example meaning)
            $table->string('byr_ntn_cnic');
            $table->text('byr_address');
            $table->string('byr_logo');
            $table->string('byr_account_number');
            $table->string('byr_reg_num');
            $table->string('byr_contact_num', 20);
            $table->string('byr_contact_person');
            $table->string('byr_IBAN');
            $table->string('byr_swift_code')->nullable();
            $table->string('byr_acc_branch_name');
            $table->string('byr_acc_branch_code')->nullable();
            $table->timestamps();
        });  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
