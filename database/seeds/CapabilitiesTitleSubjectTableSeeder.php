<?php

use Illuminate\Database\Seeder;

class CapabilitiesTitleSubjectTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();
        foreach (range(1,10) as $index) {
            DB::table('el_capabilities_title_subject')->insert([
                'capabilities_title_id' => $faker->randomElement(\Modules\Capabilities\Entities\CapabilitiesTitle::pluck('id')->toArray()),
                'subject_id' => $faker->randomElement(\App\Models\Categories\Subject::where('status', 1)->pluck('id')->toArray()),
                'level' => $faker->numberBetween(2, 4),
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }
    }
}
