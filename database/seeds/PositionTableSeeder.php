<?php

use App\Models\Categories\Position;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class PositionTableSeeder extends Seeder
{
    public function run()
    {
        foreach (range(1,500) as $index) {
            Position::updateOrCreate(['code'=>'CV'.$index],[
                'name'=> 'Chức vụ #'. ($index),
                'status'=> 1,
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s'),
            ]);
        }
    }
}
