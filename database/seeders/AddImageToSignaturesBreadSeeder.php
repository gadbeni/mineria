<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AddImageToSignaturesBreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataTypeId = \DB::table('data_types')->where('name', 'signatures')->value('id');

        if (!$dataTypeId) return;

        if (!\DB::table('data_rows')->where('data_type_id', $dataTypeId)->where('field', 'image')->exists()) {
            $maxOrder = \DB::table('data_rows')->where('data_type_id', $dataTypeId)->max('order') ?? 0;

            \DB::table('data_rows')->insert([
                'data_type_id' => $dataTypeId,
                'field'        => 'image',
                'type'         => 'image',
                'display_name' => 'Imagen de Firma',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => '{}',
                'order'        => $maxOrder + 1,
            ]);
        }
    }
}
