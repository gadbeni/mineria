<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        \DB::table('permission_role')->delete();

        $role = Role::where('name', 'admin')->firstOrFail();

        $permissions = Permission::all();

        $role->permissions()->sync(
            $permissions->pluck('id')->all()
        );


        //############## funcionario ####################
        $role = Role::where('name', 'funcionario')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "signatures" or
                                            table_name = "companies" or
                                            table_name = "certificates" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());


        // Permiso para generar los formularios
        $role = Role::where('name', 'formulario')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or
                                            table_name = "signatures" or
                                            table_name = "companies" or
                                            table_name = "certificates" or
                                            
                                            `key` = "browse_clear-cache"')->get();
        $role->permissions()->sync($permissions->pluck('id')->all());

        
        
    }
}
