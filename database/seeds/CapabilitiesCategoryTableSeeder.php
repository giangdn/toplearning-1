<?php

use Illuminate\Database\Seeder;

class CapabilitiesCategoryTableSeeder extends Seeder
{
    public function run()
    {
        foreach (range(1,5) as $index) {
            DB::table('el_capabilities_category')->insert([
                'name' => 'Danh mục '. ($index),
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }
    }
}
