<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixReportsMenuTarget extends Migration
{
    public function up()
    {
        DB::table('menu_items')
            ->whereIn('route', ['reports.form101s', 'reports.certificates'])
            ->update(['target' => '_self']);
    }

    public function down()
    {
        DB::table('menu_items')
            ->whereIn('route', ['reports.form101s', 'reports.certificates'])
            ->update(['target' => '_blank']);
    }
}
