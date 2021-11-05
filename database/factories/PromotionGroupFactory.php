<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Modules\Promotion\Entities\PromotionGroup;
use Faker\Generator as Faker;

$factory->define(PromotionGroup::class, function (Faker $faker) {
    return [
        'code' => Str::random(10),
        'name' => $faker->monthName(),
        'status' => 1,
        'created_by' => 2,
        'updated_by' => 2,
    ];
});
