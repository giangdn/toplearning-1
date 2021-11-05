<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPermissionManagerLevelTable extends Migration
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
                    'code' => 'module_manager_level',
                    'name' => 'Cấp quản lý người dùng',
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.backend.manager_level',
                'name' => 'Xem cấp quản lý người dùng',
                'parent_code' => 'module_manager_level',
                'extend' => null,
            ],
            [
                'code' => 'module.backend.manager_level.create',
                'name' => 'Thêm cấp quản lý người dùng',
                'parent_code' => 'module_manager_level',
                'extend' => null,
            ],
            [
                'code' => 'module.backend.manager_level.edit',
                'name' => 'Chỉnh sửa cấp quản lý người dùng',
                'parent_code' => 'module_manager_level',
                'extend' => 'module.backend.manager_level, module.backend.manager_level.create',
            ],
            [
                'code' => 'module.backend.manager_level.remove',
                'name' => 'Xoá cấp quản lý người dùng',
                'parent_code' => 'module_manager_level',
                'extend' => null,
            ],
            [
                'code' => 'module.backend.manager_level.approve',
                'name' => 'Duyệt / Từ chối cấp quản lý người dùng',
                'parent_code' => 'module_manager_level',
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
        Schema::dropIfExists('el_permission_manager_level');
    }
}
