<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RestrictFuncionarioToReportsOnly extends Migration
{
    public function up()
    {
        $role = DB::table('roles')->where('name', 'funcionario')->first();
        if (!$role) {
            return;
        }

        $browseAdmin = DB::table('permissions')
            ->where('key', 'browse_admin')
            ->first();

        // Quitar todos los permisos actuales del rol funcionario
        DB::table('permission_role')->where('role_id', $role->id)->delete();

        // Dejar solo browse_admin (necesario para entrar al panel /admin)
        if ($browseAdmin) {
            DB::table('permission_role')->insert([
                'permission_id' => $browseAdmin->id,
                'role_id'       => $role->id,
            ]);
        }
    }

    public function down()
    {
        $role = DB::table('roles')->where('name', 'funcionario')->first();
        if (!$role) {
            return;
        }

        $permissions = DB::table('permissions')
            ->whereIn('table_name', ['admin', 'signatures', 'companies', 'certificates'])
            ->orWhere('key', 'browse_clear-cache')
            ->pluck('id');

        $rows = [];
        foreach ($permissions as $id) {
            $rows[] = ['permission_id' => $id, 'role_id' => $role->id];
        }

        DB::table('permission_role')->insert($rows);
    }
}
