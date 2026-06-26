<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportCompaniesMenuSeeder extends Seeder
{
    public function run()
    {
        // 1. Permiso
        $permId = DB::table('permissions')->insertGetId([
            'key'        => 'browse_reportscompanies',
            'table_name' => 'reportscompanies',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Asignarlo al rol admin
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        if ($adminRoleId) {
            DB::table('permission_role')->insertOrIgnore([
                'permission_id' => $permId,
                'role_id'       => $adminRoleId,
            ]);
        }

        // 3. Ítem en el menú lateral de Voyager
        $menuId = DB::table('menus')->where('name', 'admin')->value('id');
        if ($menuId) {
            $maxOrder = DB::table('menu_items')->where('menu_id', $menuId)->max('order') ?? 0;

            DB::table('menu_items')->insert([
                'menu_id'    => $menuId,
                'title'      => 'Reporte Empresas',
                'url'        => '/admin/reports/companies',
                'target'     => '_self',
                'icon_class' => 'voyager-list',
                'color'      => null,
                'parent_id'  => null,
                'order'      => $maxOrder + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✓ Permiso creado y menú actualizado correctamente.');
    }
}
