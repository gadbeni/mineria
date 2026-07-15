<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnchangedToForm101Edits extends Migration
{
    public function up()
    {
        Schema::table('form101_edits', function (Blueprint $table) {
            $table->json('unchanged')->nullable()->after('changed'); // campos que NO se editaron
        });
    }

    public function down()
    {
        Schema::table('form101_edits', function (Blueprint $table) {
            $table->dropColumn('unchanged');
        });
    }
}
