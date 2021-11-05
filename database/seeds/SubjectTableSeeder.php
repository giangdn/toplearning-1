<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();
        $level_subjects = DB::table('el_level_subject')->get();
        
        foreach ($level_subjects as $level_subject)
        {
            foreach (range(1,5) as $index) {
                DB::table('el_subject')->insert([
                    'code'=> Str::random(5),
                    'name'=> $faker->sentence(),
                    'training_program_id'=> $level_subject->training_program_id,
                    'level_subject_id'=> $level_subject->id,
                    'status'=> 1,
                    'condition' => '1, 2, 3',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
