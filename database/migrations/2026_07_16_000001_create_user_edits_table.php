<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEditsTable extends Migration
{
    public function up()
    {
        Schema::create('user_edits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action')->nullable();   // Usuario modificado / activado / desactivado
            $table->json('before');                 // valores legibles antes
            $table->json('after');                  // valores legibles despues
            $table->json('changed');                // solo lo modificado {label: {antes, despues}}
            $table->json('unchanged');              // solo lo NO modificado {label: valor}
            $table->foreignId('edited_by')->nullable()->constrained('users');
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_edits');
    }
}
