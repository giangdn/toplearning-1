<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionPotentialTable extends Migration
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
                    'code' => 'module_potential',
                    'name' => 'Nhân sự tiềm năng',
                    'unit_permission' => 1
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.potential.index',
                'name' => 'Xem nhân sự tiềm năng',
                'parent_code' => 'module_potential',
                'extend' => null,
            ],
            [
                'code' => 'module.potential.create',
                'name' => 'Thêm nhân sự tiềm năng',
                'parent_code' => 'module_potential',
                'extend' => null,
            ],
            [
                'code' => 'module.potential.edit',
                'name' => 'Chỉnh sửa nhân sự tiềm năng',
                'parent_code' => 'module_potential',
                'extend' => 'module.potential.index, module.potential.create',
            ],
            [
                'code' => 'module.potential.remove',
                'name' => 'Xoá nhân sự tiềm năng',
                'parent_code' => 'module_potential',
                'extend' => null,
            ],
            [
                'code' => 'module.potential.export',
                'name' => 'Xuất file nhân sự tiềm năng',
                'parent_code' => 'module_potential',
                'extend' => 'module.potential.index',
            ],
            [
                'code' => 'module.potential.kpi.list_kpi',
                'name' => 'Xem danh sách KPI',
                'parent_code' => 'module_potential',
                'extend' => null,
            ],
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.potential.roadmap',
                'name' => 'Xem chương trình khung',
                'parent_code' => 'module_potential',
                'extend' => null,
            ],
            [
                'code' => 'module.potential.roadmap.create',
                'name' => 'Thêm mới chương trình khung',
                'parent_code' => 'module_potential',
                'extend' => null,
            ],
            [
                'code' => 'module.potential.roadmap.edit',
                'name' => 'Chỉnh sửa chương trình khung',
                'parent_code' => 'module_potential',
                'extend' => 'module.potential.roadmap, module.potential.roadmap.create',
            ],
            [
                'code' => 'module.potential.roadmap.remove',
                'name' => 'Xoá chương trình khung',
                'parent_code' => 'module_potential',
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
        Schema::dropIfExists('permission_potential');
    }
}
