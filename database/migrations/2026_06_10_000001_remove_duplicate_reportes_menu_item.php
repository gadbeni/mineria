<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RemoveDuplicateReportesMenuItem extends Migration
{
    public function up()
    {
        // Obtener todos los ítems con título 'Reportes'
        $reportes = DB::table('menu_items')->where('title', 'Reportes')->get();

        foreach ($reportes as $item) {
            $tieneHijos = DB::table('menu_items')->where('parent_id', $item->id)->exists();

            // Eliminar el que NO tiene hijos (el creado por código/seeder)
            if (!$tieneHijos) {
                DB::table('menu_items')->where('id', $item->id)->delete();
            }
        }
    }

    public function down()
    {
        // No reversible — el ítem fue un duplicado
    }
}
