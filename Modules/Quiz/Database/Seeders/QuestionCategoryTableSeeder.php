<?php

namespace Modules\Quiz\Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;

class QuestionCategoryTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        
        foreach (range(1,5) as $index) {
            \DB::table('el_question_category')->insert([
                'name' => $faker->name,
                'parent_id' => null,
                'created_by' => 2,
                'updated_by' => 2,
            ]);
        }
        
        foreach (range(1,5) as $index) {
            \DB::table('el_question_category')->insert([
                'name' => 'Danh muc #'. ($index),
                'parent_id' => $faker->randomElement(\Modules\Quiz\Entities\QuestionCategory::pluck('id')->toArray()),
                'created_by' => 2,
                'updated_by' => 2,
            ]);
        }
        
    }
}
