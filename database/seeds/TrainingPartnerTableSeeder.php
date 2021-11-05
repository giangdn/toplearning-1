<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class TrainingPartnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        if (!DB::table('el_training_partner')->exists()) {
            foreach (range(1,5) as $index) {
                DB::table('el_training_partner')->insert([
                    'code' => $faker->sentence(),
                    'name' => $faker->name(),
                    'people' => $faker->name(),
                    'address' => $faker->sentence(),
                    'email' => $faker->email(),
                    'phone' => $faker->phoneNumber(),
                    'created_at'=> date('Y-m-d H:i:s'),
                    'updated_at'=> date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
