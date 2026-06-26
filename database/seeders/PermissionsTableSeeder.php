<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        \DB::table('permissions')->delete();

        Permission::firstOrCreate([
            'key'        => 'browse_admin',
            'table_name' => 'admin',
        ]);

        $keys = [
            // 'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
            'browse_clear-cache'
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }
        

        Permission::generateFor('menus');

        Permission::generateFor('roles');

        Permission::generateFor('users');

        Permission::generateFor('settings');



        Permission::generateFor('form101s');

        Permission::generateFor('type_minerals');
        $keys = [
            'browse_signatures',
            'add_signatures',
            'edit_signatures',
            'read_signatures',
            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'signatures',
            ]);
        }


        $keys = [
            'browse_companies',
            'add_companies',
            'edit_companies',
            'read_companies',
            'delete_companies',
            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'companies',
            ]);
        }

        $keys = [
            'browse_certificates',
            'add_certificates',
            'delete_certificates',
            'print_certificates',
            
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => 'certificates',
            ]);
        }


    }
}
