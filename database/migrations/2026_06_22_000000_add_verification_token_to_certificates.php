<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerificationTokenToCertificates extends Migration
{
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->uuid('verification_token')->unique()->nullable()->after('registerUser_id');
        });
    }

    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('verification_token');
        });
    }
}
