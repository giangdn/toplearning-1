<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class CourseCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker\Factory::create();

        foreach (range(1,5) as $index) {
            DB::table('el_course_categories')->insert([
                'name'=> $faker->sentence(),
                'parent_id' => null,
                'type' => 1,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }

        foreach (range(1,5) as $index) {
            DB::table('el_course_categories')->insert([
                'name'=> $faker->sentence(),
                'parent_id' => null,
                'type' => 2,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }

        foreach (range(1,5) as $index) {
            DB::table('el_course_categories')->insert([
                'name'=> $faker->sentence(),
                'parent_id' => $faker->randomElement(\Modules\Online\Entities\CourseCategories::where('type', 1)->pluck('id')->toArray()),
                'type' => 1,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }

        foreach (range(1,5) as $index) {
            DB::table('el_course_categories')->insert([
                'name'=> $faker->sentence(),
                'parent_id' => $faker->randomElement(\Modules\Online\Entities\CourseCategories::where('type', 2)->pluck('id')->toArray()),
                'type' => 2,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }
    }
}
