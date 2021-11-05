<?php

use Illuminate\Database\Seeder;

class CapabilitiesGroupTableSeeder extends Seeder
{
    public function run()
    {
        foreach (range(1,5) as $index) {
            DB::table('el_capabilities_group')->insert([
                'name' => 'Nhóm '. ($index),
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }
    }
}
