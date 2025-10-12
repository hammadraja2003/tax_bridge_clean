<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fbr_post_errors', function (Blueprint $table) {
            $table->string('fbr_env', 20)->default(env('FBR_ENV', 'sandbox'))->after('raw_response');
        });
    }

    public function down(): void
    {
        Schema::table('fbr_post_errors', function (Blueprint $table) {
            $table->dropColumn('fbr_env');
        });
    }
};