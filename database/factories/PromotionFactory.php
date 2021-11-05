<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use \Modules\Promotion\Entities\Promotion;
use Faker\Generator as Faker;
use Modules\Promotion\Entities\PromotionGroup;

$factory->define(Promotion::class, function (Faker $faker) {
    $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
    return [
        'code' => $faker->unique()->countryCode,
        'name' => $faker->unique()->foodName(),
        'point' => $faker->randomNumber(2),
        'images' => "2020/06/09/0xT2ROP703_2020_06_09_14_27_41.jpg",
        'period' => $faker->dateTimeBetween('-30 days','+ 30 day'),
        'amount' => $faker->numberBetween(0,100),
        'rules' => $faker->realText(200),
        'promotion_group' => function () {
            return factory(PromotionGroup::class)->create()->id;
        },
        'contact' => $faker->phoneNumber,
        'status' => 1,
        'created_by' => 2,
        'updated_by' => 2,
    ];
});


