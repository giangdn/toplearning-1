<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTrainingPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*DB::table('el_permission')->insert(
            [
                [
                    'code' => 'module_training_plan',
                    'name' => 'Kế hoạch đào tạo',
                    'unit_permission' => 0
                ]
            ]
        );
        DB::table('el_permission')->insert([
            [
                'code' => 'module.training_plan',
                'name' => 'Xem kế hoạch đào tạo',
                'parent_code' => 'module_training_plan',
                'extend' => null,
            ],
            [
                'code' => 'module.training_plan.create',
                'name' => 'Thêm kế hoạch đào tạo',
                'parent_code' => 'module_training_plan',
                'extend' => null,
            ],
            [
                'code' => 'module.training_plan.edit',
                'name' => 'Chỉnh sửa kế hoạch đào tạo',
                'parent_code' => 'module_training_plan',
                'extend' => 'module.training_plan, module.training_plan.create',
            ],
            [
                'code' => 'module.training_plan.remove',
                'name' => 'Xoá kế hoạch đào tạo',
                'parent_code' => 'module_training_plan',
                'extend' => null,
            ],
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.training_plan.detail',
                'name' => 'Xem chi tiết kế hoạch đào tạo',
                'parent_code' => 'module_training_plan',
                'extend' => null,
            ],
            [
                'code' => 'module.training_plan.detail.create',
                'name' => 'Thêm chi tiết kế hoạch đào tạo',
                'parent_code' => 'module_training_plan',
                'extend' => null,
            ],
            [
                'code' => 'module.training_plan.detail.edit',
                'name' => 'Chỉnh sửa chi tiết kế hoạch đào tạo',
                'parent_code' => 'module_training_plan',
                'extend' => 'module.training_plan.detail, module.training_plan.detail.create',
            ],
            [
                'code' => 'module.training_plan.detail.remove',
                'name' => 'Xoá chi tiết kế hoạch đào tạo',
                'parent_code' => 'module_training_plan',
                'extend' => null,
            ],
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_training_plan');
    }
}
