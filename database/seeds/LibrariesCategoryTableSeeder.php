<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class LibrariesCategoryTableSeeder extends Seeder
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
            DB::table('el_libraries_category')->insert([
                'name'=> 'Danh mục sách #'. $index,
                'parent_id' => 0,
                'type' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }

        foreach (range(1,5) as $index) {
            DB::table('el_libraries_category')->insert([
                'name'=> 'Danh mục ebook #'. $index,
                'parent_id' => 0,
                'type' => 2,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }

        foreach (range(1,5) as $index) {
            DB::table('el_libraries_category')->insert([
                'name'=> 'Danh mục tài liệu #'. $index,
                'parent_id' => 0,
                'type' => 3,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }

        foreach (range(1,5) as $index) {
            DB::table('el_libraries_category')->insert([
                'name'=> $faker->sentence(),
                'parent_id' => $faker->randomElement(\Modules\Libraries\Entities\LibrariesCategory::where('type', 1)->pluck('id')->toArray()),
                'type' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }

        foreach (range(1,5) as $index) {
            DB::table('el_libraries_category')->insert([
                'name'=> $faker->sentence(),
                'parent_id' => $faker->randomElement(\Modules\Libraries\Entities\LibrariesCategory::where('type', 2)->pluck('id')->toArray()),
                'type' => 2,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }

        foreach (range(1,5) as $index) {
            DB::table('el_libraries_category')->insert([
                'name'=> $faker->sentence(),
                'parent_id' => $faker->randomElement(\Modules\Libraries\Entities\LibrariesCategory::where('type', 3)->pluck('id')->toArray()),
                'type' => 3,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }
    }
}
