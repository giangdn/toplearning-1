<?php

use Illuminate\Database\Seeder;

class CapabilitiesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();
        $category_id = $faker->randomElement(\Modules\Capabilities\Entities\CapabilitiesCategory::pluck('id')->toArray());
        $category_group_id = $faker->randomElement(\Modules\Capabilities\Entities\CapabilitiesCategoryGroup::where('category_id', $category_id)->pluck('id')->toArray());

        foreach (range(1,5) as $index) {
            DB::table('el_capabilities')->insert([
                'code' => 'capability' . ($index),
                'name' => 'Khung năng lực '. ($index),
                'category_id' => $category_id,
                'category_group_id' => empty($category_group_id) ? null: $category_group_id,
                'group_id' => $faker->randomElement(\Modules\Capabilities\Entities\CapabilitiesGroup::pluck('id')->toArray()),
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }
    }
}
