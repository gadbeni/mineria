<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RestrictFormularioToForm101Only extends Migration
{
    public function up()
    {
        $role = DB::table('roles')->where('name', 'formulario')->first();
        if (!$role) {
            return;
        }

        // Quitar TODOS los permisos actuales del rol formulario
        DB::table('permission_role')->where('role_id', $role->id)->delete();

        // Dejar solo: browse_admin + permisos BREAD de form101s
        $permissions = DB::table('permissions')
            ->where(function ($q) {
                $q->where('table_name', 'form101s')
                  ->orWhere('table_name', 'admin')
                  ->orWhere('key', 'browse_clear-cache');
            })
            ->pluck('id');

        $rows = $permissions->map(fn($id) => [
            'permission_id' => $id,
            'role_id'       => $role->id,
        ])->toArray();

        if (!empty($rows)) {
            DB::table('permission_role')->insert($rows);
        }
    }

    public function down()
    {
        $role = DB::table('roles')->where('name', 'formulario')->first();
        if (!$role) {
            return;
        }

        // Restaurar permisos anteriores (admin + signatures + companies + certificates + form101s)
        DB::table('permission_role')->where('role_id', $role->id)->delete();

        $permissions = DB::table('permissions')
            ->where(function ($q) {
                $q->whereIn('table_name', ['admin', 'signatures', 'companies', 'certificates', 'form101s'])
                  ->orWhere('key', 'browse_clear-cache');
            })
            ->pluck('id');

        $rows = $permissions->map(fn($id) => [
            'permission_id' => $id,
            'role_id'       => $role->id,
        ])->toArray();

        if (!empty($rows)) {
            DB::table('permission_role')->insert($rows);
        }
    }
}
