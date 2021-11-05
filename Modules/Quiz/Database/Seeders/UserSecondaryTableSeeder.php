<?php

namespace Modules\Quiz\Database\Seeders;

use Illuminate\Database\Seeder;

class UserSecondaryTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        if (!DB::table('el_quiz_user_secondary')->exists()) {
            foreach (range(1,5) as $index) {
                \DB::table('el_quiz_user_secondary')->insert([
                    'code'=> 'demo' . $index,
                    'name'=> 'Demo '. $index,
                    'username'=> 'secondary_demo'. $index,
                    'password'=> password_hash('123456@', PASSWORD_DEFAULT),
                    'dob'=> '2000-02-01 00:00:00',
                    'email' => 'secondary_demo'. $index .'@gmail.com',
                    'identity_card' => '123456789',
                    'created_at' => $faker->dateTimeBetween(),
                    'updated_at' => $faker->dateTimeBetween()
                ]);
            }
        }
    }
}
