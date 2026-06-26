<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixUsersDatatypeModelAndRegisterIdFk extends Migration
{
    public function up()
    {
        // Usar App\Models\User (con SoftDeletes) en lugar de TCG\Voyager\Models\User
        // para que Voyager haga soft-delete en vez de DELETE físico al eliminar usuarios.
        DB::table('data_types')
            ->where('slug', 'users')
            ->where('model_name', 'TCG\Voyager\Models\User')
            ->update(['model_name' => 'App\\Models\\User']);

        // Cambiar la FK register_id en form101s a ON DELETE SET NULL como red de seguridad:
        // si alguna vez se hace un DELETE físico de un usuario, el campo queda NULL.
        Schema::table('form101s', function (Blueprint $table) {
            $table->dropForeign('form101s_register_id_foreign');
            $table->foreign('register_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        DB::table('data_types')
            ->where('slug', 'users')
            ->where('model_name', 'App\\Models\\User')
            ->update(['model_name' => 'TCG\Voyager\Models\User']);

        Schema::table('form101s', function (Blueprint $table) {
            $table->dropForeign('form101s_register_id_foreign');
            $table->foreign('register_id')
                  ->references('id')
                  ->on('users');
        });
    }
}
