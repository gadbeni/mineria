<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddForm101PermissionsToFormulario extends Migration
{
    public function up()
    {
        $role = DB::table('roles')->where('name', 'formulario')->first();
        if (!$role) {
            return;
        }

        $permissions = DB::table('permissions')
            ->whereIn('key', [
                'browse_form101s',
                'read_form101s',
                'add_form101s',
                'edit_form101s',
            ])
            ->pluck('id');

        $existing = DB::table('permission_role')
            ->where('role_id', $role->id)
            ->pluck('permission_id')
            ->toArray();

        $rows = [];
        foreach ($permissions as $id) {
            if (!in_array($id, $existing)) {
                $rows[] = ['permission_id' => $id, 'role_id' => $role->id];
            }
        }

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

        $permissionIds = DB::table('permissions')
            ->whereIn('key', [
                'browse_form101s',
                'read_form101s',
                'add_form101s',
                'edit_form101s',
            ])
            ->pluck('id');

        DB::table('permission_role')
            ->where('role_id', $role->id)
            ->whereIn('permission_id', $permissionIds)
            ->delete();
    }
}
