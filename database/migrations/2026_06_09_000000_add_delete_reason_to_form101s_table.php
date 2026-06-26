<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteReasonToForm101sTable extends Migration
{
    public function up()
    {
        Schema::table('form101s', function (Blueprint $table) {
            $table->text('delete_reason')->nullable()->after('deleted_id');
        });
    }

    public function down()
    {
        Schema::table('form101s', function (Blueprint $table) {
            $table->dropColumn('delete_reason');
        });
    }
}
