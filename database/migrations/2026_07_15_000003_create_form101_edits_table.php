<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForm101EditsTable extends Migration
{
    public function up()
    {
        Schema::create('form101_edits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form101_id')->constrained('form101s')->onDelete('cascade');
            $table->json('before');          // valores antes de editar
            $table->json('after');           // valores despues de editar
            $table->json('changed');         // solo campos que cambiaron (before/after)
            $table->foreignId('edited_by')->nullable()->constrained('users');
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form101_edits');
    }
}
