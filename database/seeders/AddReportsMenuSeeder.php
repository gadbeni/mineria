<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddReportsMenuSeeder extends Seeder
{
    public function run()
    {
        $existe = DB::table('menu_items')
            ->where('title', 'Reportes')
            ->where('menu_id', 1)
            ->whereNull('parent_id')
            ->exists();

        if ($existe) {
            $this->command->info('El menú de Reportes ya existe. No se creó duplicado.');
            return;
        }

        $parentId = DB::table('menu_items')->insertGetId([
            'menu_id'    => 1,
            'title'      => 'Reportes',
            'url'        => '',
            'target'     => '_self',
            'icon_class' => 'fa-solid fa-chart-bar',
            'color'      => '#000000',
            'parent_id'  => null,
            'order'      => 5,
            'route'      => null,
            'parameters' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('menu_items')->insert([
            [
                'menu_id'    => 1,
                'title'      => 'Formularios 101',
                'url'        => '',
                'target'     => '_self',
                'icon_class' => 'fa-regular fa-file-lines',
                'color'      => '#000000',
                'parent_id'  => $parentId,
                'order'      => 1,
                'route'      => 'reports.form101s',
                'parameters' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_id'    => 1,
                'title'      => 'Certificados',
                'url'        => '',
                'target'     => '_self',
                'icon_class' => 'fa-regular fa-file-certificate',
                'color'      => '#000000',
                'parent_id'  => $parentId,
                'order'      => 2,
                'route'      => 'reports.certificates',
                'parameters' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info("Menú de Reportes agregado con ID de padre: {$parentId}");
    }
}
