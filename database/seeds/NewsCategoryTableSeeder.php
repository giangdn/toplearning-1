<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class NewsCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1,5) as $index) {
            DB::table('el_news_category')->insert([
                'name' => 'Danh mục tin tức #'. $index,
                'created_by' => 2,
                'updated_by' => 2,
                'parent_id' => null,
                'created_at' => date('Y-m-d H:I:s'),
                'updated_at' => date('Y-m-d H:I:s'),
            ]);
        }
    }
}
