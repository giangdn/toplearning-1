<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LevelSubjectTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();
        $training_programs = DB::table('el_training_program')->get();
        foreach ($training_programs as $training_program)
        {
            foreach (range(1,5) as $index) {
                DB::table('el_level_subject')->insert([
                    'code'=> Str::random(5),
                    'name'=> $faker->sentence(),
                    'training_program_id'=> $training_program->id,
                    'status'=> 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
