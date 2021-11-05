<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Promotion\Entities\PromotionLevel;

class PromotionLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        factory(PromotionLevel::class,5)->create();

        Model::reguard();
    }
}
