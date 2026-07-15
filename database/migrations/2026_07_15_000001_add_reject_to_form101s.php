<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRejectToForm101s extends Migration
{
    public function up()
    {
        Schema::table('form101s', function (Blueprint $table) {
            $table->text('reject_reason')->nullable()->after('delete_reason');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->after('reject_reason');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
        });
    }

    public function down()
    {
        Schema::table('form101s', function (Blueprint $table) {
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['reject_reason', 'rejected_by', 'rejected_at']);
        });
    }
}
