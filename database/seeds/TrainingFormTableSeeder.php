<?php

use Faker\Factory;
use Illuminate\Database\Seeder;

class TrainingFormTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(1,5) as $index) {
            DB::table('el_training_form')->insert([
                'code'=> Str::random(5),
                'name'=> 'Hình thức đào tạo #'. $index,
                'created_at' => $faker->dateTimeBetween(),
                'updated_at' => $faker->dateTimeBetween()
            ]);
        }
    }
}
