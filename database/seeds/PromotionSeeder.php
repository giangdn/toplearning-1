<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Promotion\Entities\Promotion;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        factory(Promotion::class, 10)->create();

        Model::reguard();
    }
}
