<?php

use App\Models\Categories\Position;
use App\Models\Categories\Titles;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class TitlesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        foreach (range(1,500) as $index) {
            Titles::updateOrCreate(['code'=>'CD'.$index],[
                'name'=> 'Chức danh #'. ($index),
                'status'=> 1,
                'position_id'=> $faker->randomElement(Position::pluck('id')->toArray()),
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        }
    }
}
