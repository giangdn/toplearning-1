<?php

use Illuminate\Database\Seeder;

class ProvinceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (range(1,64) as $index) {
            DB::table('el_province')->insert([
                /*'id'=> $index,*/
                'name'=> 'Tỉnh thành '. ($index),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
