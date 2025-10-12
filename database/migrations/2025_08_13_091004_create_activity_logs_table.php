<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('device_id')->nullable();
            $table->string('action', 50);
            $table->text('description')->nullable();
            $table->string('record_id')->nullable();
            $table->string('table_name')->nullable();
            $table->string('data_hash', 64);
            $table->boolean('hash_changed')->default(true);
            $table->longText('data')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}