<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForm101sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form101s', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->foreignId('certificate_id')->nullable()->constrained('certificates');

            $table->foreignId('typeMineral_id')->nullable()->constrained('type_minerals');
            $table->string('leyMineral')->nullable();

            $table->decimal('pesoBruto', 11,2)->nullable();
            $table->string('humedad')->nullable();
            $table->decimal('pesoNeto', 11,2)->nullable();

            $table->string('lote')->nullable();

            $table->string('municipio')->nullable();
            $table->string('localidad')->nullable();
            $table->string('codigoAreaMinero')->nullable();
            $table->string('nombreAreaMinero')->nullable();

            $table->string('medioTransporte')->nullable();
            $table->string('origen')->nullable();
            $table->string('final')->nullable();
            $table->string('matricula')->nullable();

            $table->string('nombreConductor')->nullable();
            $table->string('licenciaConducir')->nullable();

            $table->string('nombreEncargadoTrasporte')->nullable();
            $table->string('ciEncargadoTrasporte')->nullable();

            $table->text('observation')->nullable();


            $table->timestamps();
            $table->foreignId('register_id')->nullable()->constrained('users');
            $table->softDeletes();
            $table->foreignId('deleted_id')->nullable()->constrained('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form101s');
    }
}
