<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Modules\Promotion\Entities\PromotionLevel;

$factory->define(PromotionLevel::class, function (Faker $faker) {
    static $number = 1;
    static $point = 1000;
    return [
        'code' => Str::random(10),
        'point' => $point+=1000,
        'name' =>$faker->unique()->colorName,
        'level' => $number++,
        'images' => $faker->imageUrl('640','480','technics'),
        'status' => 1,
        'created_by' => 2,
        'updated_by' => 2,
    ];
});
