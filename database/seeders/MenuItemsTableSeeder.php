<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_items')->delete();
        
        \DB::table('menu_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'menu_id' => 1,
                'title' => 'Inicio',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-house',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 1,
                'created_at' => '2022-11-25 13:39:03',
                'updated_at' => '2023-05-30 14:01:00',
                'route' => 'voyager.dashboard',
                'parameters' => 'null',
            ),
            1 => 
            array (
                'id' => 2,
                'menu_id' => 1,
                'title' => 'Media',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-images',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 1,
                'created_at' => '2022-11-25 13:39:03',
                'updated_at' => '2022-11-25 13:59:24',
                'route' => 'voyager.media.index',
                'parameters' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'menu_id' => 1,
                'title' => 'Usuarios',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-person',
                'color' => '#000000',
                'parent_id' => 15,
                'order' => 1,
                'created_at' => '2022-11-25 13:39:03',
                'updated_at' => '2023-11-27 18:46:21',
                'route' => 'voyager.users.index',
                'parameters' => 'null',
            ),
            3 => 
            array (
                'id' => 4,
                'menu_id' => 1,
                'title' => 'Roles',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => NULL,
                'parent_id' => 15,
                'order' => 2,
                'created_at' => '2022-11-25 13:39:03',
                'updated_at' => '2022-11-25 13:59:07',
                'route' => 'voyager.roles.index',
                'parameters' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'menu_id' => 1,
                'title' => 'Herramientas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-tools',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 9,
                'created_at' => '2022-11-25 13:39:03',
                'updated_at' => '2023-11-27 18:46:02',
                'route' => NULL,
                'parameters' => '',
            ),
            5 => 
            array (
                'id' => 6,
                'menu_id' => 1,
                'title' => 'Menu Builder',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-list',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 2,
                'created_at' => '2022-11-25 13:39:03',
                'updated_at' => '2022-11-25 13:59:24',
                'route' => 'voyager.menus.index',
                'parameters' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'menu_id' => 1,
                'title' => 'Database',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-data',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 3,
                'created_at' => '2022-11-25 13:39:03',
                'updated_at' => '2022-11-25 13:59:24',
                'route' => 'voyager.database.index',
                'parameters' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'menu_id' => 1,
                'title' => 'Compass',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-compass',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 4,
                'created_at' => '2022-11-25 13:39:03',
                'updated_at' => '2022-11-25 13:59:24',
                'route' => 'voyager.compass.index',
                'parameters' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'menu_id' => 1,
                'title' => 'BREAD',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-bread',
                'color' => NULL,
                'parent_id' => 5,
                'order' => 5,
                'created_at' => '2022-11-25 13:39:03',
                'updated_at' => '2022-11-25 13:59:24',
                'route' => 'voyager.bread.index',
                'parameters' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'menu_id' => 1,
                'title' => 'Configuración',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-settings',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 8,
                'created_at' => '2022-11-25 13:39:03',
                'updated_at' => '2023-11-27 18:46:10',
                'route' => 'voyager.settings.index',
                'parameters' => 'null',
            ),
            10 => 
            array (
                'id' => 14,
                'menu_id' => 1,
                'title' => 'Parámetros',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-params',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 5,
                'created_at' => '2022-11-25 13:55:57',
                'updated_at' => '2023-11-27 18:19:03',
                'route' => NULL,
                'parameters' => '',
            ),
            11 => 
            array (
                'id' => 15,
                'menu_id' => 1,
                'title' => 'Seguridad',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-lock',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 7,
                'created_at' => '2022-11-25 13:58:42',
                'updated_at' => '2023-06-29 14:16:26',
                'route' => NULL,
                'parameters' => '',
            ),
            12 => 
            array (
                'id' => 19,
                'menu_id' => 1,
                'title' => 'Empresas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-building',
                'color' => NULL,
                'parent_id' => NULL,
                'order' => 2,
                'created_at' => '2022-11-25 15:44:39',
                'updated_at' => '2023-11-27 18:18:49',
                'route' => 'voyager.companies.index',
                'parameters' => NULL,
            ),
            13 => 
            array (
                'id' => 21,
                'menu_id' => 1,
                'title' => 'Firmas',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-signature',
                'color' => NULL,
                'parent_id' => 25,
                'order' => 2,
                'created_at' => '2022-11-25 16:23:40',
                'updated_at' => '2023-06-29 14:16:26',
                'route' => 'voyager.signatures.index',
                'parameters' => NULL,
            ),
            14 => 
            array (
                'id' => 22,
                'menu_id' => 1,
                'title' => 'Certificados',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-regular fa-file-lines',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 3,
                'created_at' => '2022-11-25 18:43:36',
                'updated_at' => '2023-11-27 18:18:59',
                'route' => 'certificates.index',
                'parameters' => 'null',
            ),
            15 => 
            array (
                'id' => 23,
                'menu_id' => 1,
                'title' => 'Formulario 101',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-regular fa-file-lines',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 4,
                'created_at' => '2023-06-27 15:54:37',
                'updated_at' => '2023-11-27 18:19:03',
                'route' => 'form101s.index',
                'parameters' => NULL,
            ),
            16 => 
            array (
                'id' => 24,
                'menu_id' => 1,
                'title' => 'Tipos de Minerales',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'fa-solid fa-coins',
                'color' => NULL,
                'parent_id' => 25,
                'order' => 1,
                'created_at' => '2023-06-29 14:14:56',
                'updated_at' => '2023-06-29 14:16:18',
                'route' => 'voyager.type-minerals.index',
                'parameters' => NULL,
            ),
            17 => 
            array (
                'id' => 25,
                'menu_id' => 1,
                'title' => 'Parametros',
                'url' => '',
                'target' => '_self',
                'icon_class' => 'voyager-params',
                'color' => '#000000',
                'parent_id' => NULL,
                'order' => 6,
                'created_at' => '2023-06-29 14:16:11',
                'updated_at' => '2023-06-29 14:16:26',
                'route' => NULL,
                'parameters' => '',
            ),
        ));
        
        
    }
}