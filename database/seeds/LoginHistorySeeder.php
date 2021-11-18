<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoginHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(11, 200000) as $index) {
            DB::table('el_login_history')->insert([
                'user_id' => $faker->numberBetween(1, 1234567890),
                'user_code' => 'admin',
                'user_name' => $faker->word(),
                'number_hits' => $index,
                'ip_address' => $faker->ipv4(),
                'user_type' => 1,
                'created_at' => '2021-11-18 09:17:11',
                'updated_at' => '2021-11-18 09:17:11'
            ]);
        }
    }
}
