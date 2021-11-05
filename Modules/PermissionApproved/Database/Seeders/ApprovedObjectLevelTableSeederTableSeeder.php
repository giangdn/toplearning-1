<?php

namespace Modules\PermissionApproved\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\PermissionApproved\Entities\ApprovedObjectLevel;

class ApprovedObjectLevelTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $levels =[
            ['key'=>1,'name'=>'Trực tiếp'],
            ['key'=>2,'name'=>'Trên 1 cấp'],
            ['key'=>3,'name'=>'Trên 2 cấp']
        ];
        foreach ($levels as $index => $item) {
            ApprovedObjectLevel::updateOrCreate(['id'=>$item['key']],
                [
                    'name'=>$item['name'],
                ]
            );
        }

    }
}
