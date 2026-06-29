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
            ->orWhereIn('key', ['browse_reportsform101s', 'browse_reportscertificates', 'browse_respotsempresas'])
            ->get();
        $role->permissions()->sync($permissions->pluck('id')->all());


        // Rol formulario: solo Formulario 101 y browse_admin
        $role = Role::where('name', 'formulario')->firstOrFail();
        $permissions = Permission::where('table_name', 'form101s')
            ->orWhere('key', 'browse_admin')
            ->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

    }
}
