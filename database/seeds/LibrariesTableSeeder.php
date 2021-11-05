<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class LibrariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();

        foreach (range(1,20) as $index) {
            DB::table('el_libraries')->insert([
                'name' => 'Thư viện sách #'. $index,
                'image' => '',
                'views' => 1,
                'download' => 1,
                'current_number'=>1,
                'status' => 1,
                'description' => $faker->paragraph(50),
                'type' => 1,
                'category_id' => $faker->randomElement(\Modules\Libraries\Entities\LibrariesCategory::where('type', 1)->pluck('id')->toArray()),
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }

        foreach (range(1,20) as $index) {
            DB::table('el_libraries')->insert([
                'name' => 'Thư viện ebook #'. $index,
                'image' => '',
                'views' => 1,
                'download' => 1,
                'status' => 1,
                'description' => $faker->sentence(),
                'type' => 2,
                'category_id' => $faker->randomElement(\Modules\Libraries\Entities\LibrariesCategory::where('type', 2)->pluck('id')->toArray()),
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }

        foreach (range(1,20) as $index) {
            DB::table('el_libraries')->insert([
                'name' => 'Thư viện tài liệu #'. $index,
                'image' => '',
                'views' => 1,
                'download' => 1,
                'status' => 1,
                'description' => $faker->sentence(),
                'type' => 3,
                'category_id' => $faker->randomElement(\Modules\Libraries\Entities\LibrariesCategory::where('type', 3)->pluck('id')->toArray()),
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }
    }
}
