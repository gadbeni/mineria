<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('nit')->nullable();
            $table->string('nim')->nullable();
            
            $table->string('razon')->nullable();

            $table->string('codeMiningOperator')->nullable();

            $table->text('activity')->nullable();
            $table->string('representative')->nullable();
            $table->string('ci')->nullable();
            $table->string('phone')->nullable();


            $table->string('municipe')->nullable();
            $table->string('municipeMiningOperator')->nullable();


            $table->smallInteger('status')->default(1);

            $table->timestamps();
            $table->foreignId('registerUser_id')->nullable()->constrained('users');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
