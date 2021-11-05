<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class NewsTableSeeder extends Seeder
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

        //
        foreach (range(1,20) as $index) {
            DB::table('el_news')->insert([
                'title' => $faker->sentence(),
                'content' => $faker->paragraph(50),
                'description' => $faker->paragraph(50),
                'type' => 1,
                'image' => '',
                'views' => 1,
                'status' => 1,
                'category_id' => $faker->randomElement(\Modules\News\Entities\NewsCategory::pluck('id')->toArray()),
                'created_by' => 2,
                'updated_by' => 2,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }
    }
}
