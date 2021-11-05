<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTrainingRoadmapTable extends Migration
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
                    'code' => 'module_training_roadmap',
                    'name' => 'Chương trình khung',
                    'unit_permission' => 0
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.trainingroadmap.detail',
                'name' => 'Xem chương trình khung',
                'parent_code' => 'module_training_roadmap',
                'extend' => null,
            ],
            [
                'code' => 'module.trainingroadmap.detail.create',
                'name' => 'Thêm mới chương trình khung',
                'parent_code' => 'module_training_roadmap',
                'extend' => null,
            ],
            [
                'code' => 'module.trainingroadmap.detail.edit',
                'name' => 'Chỉnh sửa chương trình khung',
                'parent_code' => 'module_training_roadmap',
                'extend' => 'module.trainingroadmap.detail, module.trainingroadmap.detail.create',
            ],
            [
                'code' => 'module.trainingroadmap.detail.remove',
                'name' => 'Xoá chương trình khung',
                'parent_code' => 'module_training_roadmap',
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
        Schema::dropIfExists('permission_training_roadmap');
    }
}
