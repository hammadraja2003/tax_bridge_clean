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
        Schema::create('sandbox_scenarios', function (Blueprint $table) {
            $table->bigIncrements('scenario_id'); // custom PK
            $table->string('scenario_code', 10)->unique();
            $table->string('scenario_description', 255);
            $table->string('sale_type', 255);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sandbox_scenarios');
    }
};