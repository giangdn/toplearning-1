<?php

use App\Models\Categories\Unit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();
        //if (!DB::table('el_unit')->exists()) {
        foreach (range(1,10) as $index) {
            $model = Unit::firstOrNew(['code'=>'unit'.$index]);
            $model->name = 'Đơn vị cấp #'. $index;
            $model->level = $index;
            $model->parent_code = $index == 1 ? null : 'unit'.($index - 1);
            $model->status = 1;
            $model->created_at = $faker->dateTimeBetween();
            $model->updated_at = $faker->dateTimeBetween();
            $model->save();
        }
        foreach (range(11,100) as $index) {
            $model = Unit::firstOrNew(['code'=>'unit'.$index]);
            $ran = random_int(2,10);
            $model->name = 'Đơn vị cấp #'. $index;
            $model->level = $ran;
            $model->parent_code = $ran == 1 ? null : 'unit'.($ran - 1);
            $model->status = 1;
            $model->created_at = $faker->dateTimeBetween();
            $model->updated_at = $faker->dateTimeBetween();
            $model->save();
        }
    }
}
