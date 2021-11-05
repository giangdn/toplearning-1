<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Promotion\Entities\PromotionGroup;

class PromotionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        factory(PromotionGroup::class,5)->create();

        Model::reguard();
    }
}
