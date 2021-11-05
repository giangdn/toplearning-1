<?php

use Illuminate\Database\Seeder;

class CapabilitiesTitleTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();
        foreach (range(1,5) as $index) {
            $level = $faker->numberBetween(2, 4);
            $critical_level = $faker->numberBetween(1, 5);
            $weight = $faker->numberBetween(10, 50);

            DB::table('el_capabilities_title')->insert([
                'number_title' => $index,
                'capabilities_id' => $faker->randomElement(\Modules\Capabilities\Entities\Capabilities::pluck('id')->toArray()),
                'title_id' => $faker->randomElement(\App\Models\Categories\Titles::where('status', 1)->pluck('id')->toArray()),
                'weight' => $weight,
                'critical_level' => $critical_level,
                'level' => $level,
                'goal' => \Modules\Capabilities\Entities\CapabilitiesTitle::getGoal($level, $critical_level, $weight),
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }
    }
}
