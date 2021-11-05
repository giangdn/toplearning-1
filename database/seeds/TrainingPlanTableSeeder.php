<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class TrainingPlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        if (!DB::table('el_training_plan')->exists()) {
            foreach (range(1,3) as $index) {
                DB::table('el_training_plan')->insert([
                    'code'=> Str::random(10),
                    'name'=> $faker->sentence(),
                    'created_at'=> date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
