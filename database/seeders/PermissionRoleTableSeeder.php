<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->delete();

        $role = Role::where('name', 'admin')->firstOrFail();

        $permissions = Permission::all();

        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );


        //############## funcionario ####################
        // browse_admin + permisos de menú de Reportes
        $role = Role::where('name', 'funcionario')->firstOrFail();
        $permissions = Permission::where('key', 'browse_admin')
            ->orWhereIn('key', ['browse_reportsform101s', 'browse_reportscertificates'])
            ->get();
        $role->permissions()->sync($permissions->pluck('id')->all());


        // Rol formulario: solo puede crear y gestionar Formulario 101
        // No tiene acceso a Empresas, Certificados, Firmas ni Parámetros
        $role = Role::where('name', 'formulario')->firstOrFail();
        $permissions = Permission::where(function ($q) {
            $q->where('table_name', 'form101s')
              ->orWhere('table_name', 'admin')
              ->orWhere('key', 'browse_clear-cache');
        })->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        
        
    }
}
