<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use Illuminate\Support\Str;

class TrainingProgramTableSeeder extends Seeder
{
    public function run()
    {
        if (!DB::table('el_training_program')->exists())
        {
            foreach (range(1,3) as $index) {
                DB::table('el_training_program')->insert([
                    'code'=> Str::random(10),
                    'name'=> 'Chương trình đào tạo #'. $index,
                    'status'=> 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
