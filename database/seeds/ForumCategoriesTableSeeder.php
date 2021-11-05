<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class ForumCategoriesTableSeeder extends Seeder
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
            DB::table('el_forum_category')->insert([
                'name'=> 'danh muc #'.$index,
                'icon' => 'path',
                'status' => 1,
            ]);
        }

        foreach (range(1,10) as $index) {
            DB::table('el_forum')->insert([
                'name' => 'chuyen muc #'.$index,
                'category_id' => $faker->randomElement(\Modules\Forum\Entities\ForumCategory::pluck('id')->toArray()),
                'created_by' => 2,
                'updated_by' => 2,
                'status' => 1,
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),

            ]);
        }

    }
}
