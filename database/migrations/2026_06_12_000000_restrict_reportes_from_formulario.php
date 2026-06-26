<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RestrictReportesFromFormulario extends Migration
{
    public function up()
    {
        // Permisos que controlan la visibilidad de los ítems del menú Reportes.
        // Voyager calcula el slug como: link_sin_base_url sin slashes → 'browse_' + slug
        // reports.form101s  → /reports/form101s  → reportsform101s → browse_reportsform101s
        // reports.certificates → /reports/certificates → reportscertificates → browse_reportscertificates
        $keys = ['browse_reportsform101s', 'browse_reportscertificates'];

        foreach ($keys as $key) {
            if (!DB::table('permissions')->where('key', $key)->exists()) {
                DB::table('permissions')->insert(['key' => $key, 'table_name' => 'reports']);
            }
        }

        $permIds = DB::table('permissions')->whereIn('key', $keys)->pluck('id');

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
