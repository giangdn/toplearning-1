<?php

use Illuminate\Database\Seeder;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $arr_file_type = ['image/png', 'image/jpeg', 'image/gif', 'video/mp4', 'audio/mp3'];

        foreach (range(1,50) as $index) {
            DB::table('el_warehouse')->insert([
                'file_name'=> $faker->sentence(2),
                'file_type'=> array_rand($arr_file_type, 1),
                'file_path' => 'im',
                'file_size' => $faker->randomNumber(),
                'type' => 2,
                'source' => 'create',
                'parent_id' => null,
                'created_by' => 2,
                'updated_by' => 2,
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }

        foreach (range(1,50) as $index) {
            DB::table('el_warehouse')->insert([
                'file_name'=> $faker->sentence(3),
                'file_type'=> array_rand($arr_file_type, 1),
                'file_path' => 'im',
                'file_size' => $faker->randomNumber(),
                'type' => 1,
                'source' => 'upload',
                'parent_id' => $faker->randomElement(\App\Warehouse::pluck('id')->toArray()),
                'created_by' => 2,
                'updated_by' => 2,
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }
    }
}
