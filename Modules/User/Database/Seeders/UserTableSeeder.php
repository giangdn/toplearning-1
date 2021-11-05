<?php

namespace Modules\User\Database\Seeders;

use App\Profile;
use Faker\Factory;
use App\Models\Categories\Area;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $user_superadmin= \DB::table('user')->insertGetId([
            'auth' => 'manual',
            'username' => 'superadmin',
            'password' => \Hash::make('superAdmin123@@'),
            'email' => 'supperadmin@gmail.com',
            'firstname' => 'super',
            'lastname' => 'admin'
        ]);

        $profile=Profile::firstOrNew(['id'=>$user_superadmin],[
            'code'=> 'superadmin',
            'id' => $user_superadmin,
            'user_id' => $user_superadmin,
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'superadmin@gmail.com',
            'status'=> 1,
            'dob' => date('Y-m-d 00:00:00'),
            'date_range' => date('Y-m-d H:i:s'),
            'contract_signing_date'=> date('Y-m-d H:i:s'),
            'effective_date'=> date('Y-m-d H:i:s'),
            'expiration_date'=> date('Y-m-d H:i:s'),
            'gender'=> 1,
            'title_code' => $faker->randomElement(Titles::where('status', '=', 1)->pluck('code')->toArray()),
            'title_id' => $faker->randomElement(Titles::where('status', '=', 1)->pluck('id')->toArray()),
            'unit_code' => $faker->randomElement(Unit::where('status', '=', 1)->pluck('code')->toArray()),
            'unit_id' => $faker->randomElement(Unit::where('status', '=', 1)->pluck('id')->toArray()),
            'area_code' => $faker->randomElement(Area::where('status', '=', 1)->pluck('code')->toArray()),
            'phone' => '0123456789',
        ]);
        $profile->save();
        $user_admin = \DB::table('user')->insertGetId([
            'auth' => 'manual',
            'username' => 'admin',
            'password' => \Hash::make('Admin123@@'),
            'email' => 'admin@gmail.com',
            'firstname' => 'Admin',
            'lastname' => 'User'
        ]);
        $profile = Profile::firstOrNew(['id'=>$user_admin],[
            'code'=> 'admin',
            'id' => $user_admin,
            'user_id' => $user_admin,
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@gmail.com',
            'status'=> 1,
            'dob' => date('Y-m-d 00:00:00'),
            'date_range' => date('Y-m-d H:i:s'),
            'contract_signing_date'=> date('Y-m-d H:i:s'),
            'effective_date'=> date('Y-m-d H:i:s'),
            'expiration_date'=> date('Y-m-d H:i:s'),
            'gender'=> 1,
            'title_code' => $faker->randomElement(Titles::where('status', '=', 1)->pluck('code')->toArray()),
            'title_id' => $faker->randomElement(Titles::where('status', '=', 1)->pluck('id')->toArray()),
            'unit_code' => $faker->randomElement(Unit::where('status', '=', 1)->pluck('code')->toArray()),
            'unit_id' => $faker->randomElement(Unit::where('status', '=', 1)->pluck('id')->toArray()),
            'area_code' => $faker->randomElement(Area::where('status', '=', 1)->pluck('code')->toArray()),
            'phone' => '0123456789',
        ]);
        $profile->save();
//        \DB::table('el_profile')
//            ->where('user_id', '=', $user_id)
//            ->update([
//                'role' => 1,
//            ]);
    }
}
