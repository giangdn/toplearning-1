<?php

use Illuminate\Database\Seeder;

class CapabilitiesCategoryGroupTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();
        foreach (range(1,15) as $index) {
            DB::table('el_capabilities_category_group')->insert([
                'name' => 'G' . ($index),
                'category_id' => $faker->randomElement(\Modules\Capabilities\Entities\CapabilitiesCategory::pluck('id')->toArray()),
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }
    }
}
