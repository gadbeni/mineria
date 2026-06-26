<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SignaturesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('signatures')->delete();
        
        \DB::table('signatures')->insert(array (
            0 => 
            array (
                'id' => 1,
                'alias' => 'Ing.',
                'first_name' => 'Nathaly',
                'last_name' => 'Davilia Antezana',
                'job' => 'SECRETARIA DEPARTAMENTAL DE MINERIA, ENERGIA E HIDROCARBUROS A.I',
                'status' => 1,
                'created_at' => '2022-11-26 21:52:11',
                'updated_at' => '2022-11-26 21:52:11',
                'registerUser_id' => 1,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}