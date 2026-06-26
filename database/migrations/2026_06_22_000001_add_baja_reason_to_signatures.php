<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBajaReasonToSignatures extends Migration
{
    public function up()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->text('baja_reason')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('signatures', function (Blueprint $table) {
            $table->dropColumn('baja_reason');
        });
    }
}
