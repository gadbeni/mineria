<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfirmacionToForm101s extends Migration
{
    public function up()
    {
        Schema::table('form101s', function (Blueprint $table) {
            $table->string('status')->default('Borrador')->after('observation');
            $table->timestamp('confirmed_at')->nullable()->after('status');
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->after('confirmed_at');
            $table->foreignId('signature_id')->nullable()->constrained('signatures')->after('confirmed_by');
        });
    }

    public function down()
    {
        Schema::table('form101s', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropForeign(['signature_id']);
            $table->dropColumn(['status', 'confirmed_at', 'confirmed_by', 'signature_id']);
        });
    }
}
