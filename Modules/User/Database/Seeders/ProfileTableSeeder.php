<?php

namespace Modules\User\Database\Seeders;

use App\Profile;
use Illuminate\Database\Seeder;
use App\Models\Categories\Area;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Faker\Factory;

class ProfileTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        foreach (range(3,50) as $index) {
            $username = 'demo'. ($index);

            $user = \DB::table('user')
                ->where('username', '=', $username)
                ->first();

            if (empty($user)) {
                \DB::table('user')->insert([
                    'auth' => 'manual',
                    'username' => $username,
                    'password' => \Hash::make('User123@@'),
                    'email' => $username . '@gmail.com',
                    'firstname' => $faker->lastName,
                    'lastname' => $faker->firstName
                ]);

                $user = \DB::table('user')
                    ->where('username', '=', $username)
                    ->first();
            }

            /*if (\DB::table('el_profile')
                ->where('user_id', '=', $user->id)
                ->exists()) {
                continue;
            }*/
            $model = Profile::firstOrNew(['user_id'=>$user->id]);
            $model->user_id = $user->id;
            $model->code = $username;
            $model->firstname = $index;
            $model->lastname = 'Demo';
            $model->email = $username . '@gmail.com';
            $model->status = 1;
            $model->dob = date('Y-m-d 00:00:00');
            $model->date_range = date('Y-m-d H:i:s');
            $model->contract_signing_date = date('Y-m-d H:i:s');
            $model->effective_date = date('Y-m-d H:i:s');
            $model->expiration_date = date('Y-m-d H:i:s');
            $model->gender = 1;
            $model->title_code = $faker->randomElement(Titles::where('status', '=', 1)->pluck('code')->toArray());
            $model->title_id = $faker->randomElement(Titles::where('status', '=', 1)->pluck('id')->toArray());
            $model->unit_code = $faker->randomElement(Unit::where('status', '=', 1)->pluck('code')->toArray());
            $model->unit_id = $faker->randomElement(Unit::where('status', '=', 1)->pluck('id')->toArray());
            $model->area_code = $faker->randomElement(Area::where('status', '=', 1)->pluck('code')->toArray());
            $model->phone = '0123456789';
            $model->created_at = now();
            $model->updated_at = now();
            $model->save();
        }
    }
}
