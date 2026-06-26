<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class EnsureReportPermissionsExist extends Migration
{
    public function up()
    {
        $keys = [
            ['key' => 'browse_reportsform101s',    'table_name' => 'reports'],
            ['key' => 'browse_reportscertificates', 'table_name' => 'reports'],
        ];

        foreach ($keys as $perm) {
            if (!DB::table('permissions')->where('key', $perm['key'])->exists()) {
                DB::table('permissions')->insert(array_merge($perm, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // Asignar ambos permisos a admin y funcionario si no los tienen
        $permIds = DB::table('permissions')
            ->whereIn('key', ['browse_reportsform101s', 'browse_reportscertificates'])
            ->pluck('id');

        $roles = DB::table('roles')->whereIn('name', ['admin', 'funcionario'])->get();

        foreach ($roles as $role) {
            foreach ($permIds as $permId) {
                $exists = DB::table('permission_role')
                    ->where('permission_id', $permId)
                    ->where('role_id', $role->id)
                    ->exists();

                if (!$exists) {
                    DB::table('permission_role')->insert([
                        'permission_id' => $permId,
                        'role_id'       => $role->id,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        $permIds = DB::table('permissions')
            ->whereIn('key', ['browse_reportsform101s', 'browse_reportscertificates'])
            ->pluck('id');

        DB::table('permission_role')->whereIn('permission_id', $permIds)->delete();
        DB::table('permissions')
            ->whereIn('key', ['browse_reportsform101s', 'browse_reportscertificates'])
            ->delete();
    }
}
