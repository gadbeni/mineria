<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForm101RejectionsTable extends Migration
{
    public function up()
    {
        Schema::create('form101_rejections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form101_id')->constrained('form101s')->onDelete('cascade');
            $table->text('reject_reason');
            $table->foreignId('rejected_by')->nullable()->constrained('users');
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form101_rejections');
    }
}
