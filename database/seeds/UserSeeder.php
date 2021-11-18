<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(3, 500000) as $index) {
            DB::table('user')->insert([
                'auth' => 'manual',
                'username' => $faker->word() . $index,
                'password' => '$2y$10$kqf692OiCVHvX3VMSSc9nug0A6DJGTUgEllnWbJ3N0F8yPlK7/zJW',
                'firstname' => $faker->word(),
                'lastname' => $faker->word(),
                'email' => $faker->email(),
                'last_online' => '2021-11-18 09:17:11'
            ]);
        }
    }
}
