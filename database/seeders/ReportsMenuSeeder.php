<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Elimina los ítems de menú de Reportes creados por seeder (IDs 26, 27, 28).
 * Los ítems correctos fueron creados manualmente desde el Menu Builder de Voyager.
 */
class ReportsMenuSeeder extends Seeder
{
    public function run()
    {
        DB::table('menu_items')->whereIn('id', [26, 27, 28])->delete();
        $this->command->info('Ítems duplicados de Reportes eliminados (IDs 26, 27, 28).');
    }
}
