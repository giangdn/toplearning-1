<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class OfflineRegisterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        foreach (range(1,25) as $index) {
            $course_id = $faker->randomElement(\Modules\Offline\Entities\OfflineCourse::where('status', 1)->pluck('id')->toArray());
            $user_id = $faker->randomElement(\App\Profile::where('status', 1)->pluck('user_id')->toArray());

            DB::table('el_offline_register')->insert([
                'user_id'=> $user_id,
                'course_id'=> $course_id,
                'status'=> 1,
                'note'=> $faker->sentence(),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
