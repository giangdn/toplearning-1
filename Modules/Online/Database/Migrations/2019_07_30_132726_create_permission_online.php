<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionOnline extends Migration
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
                    'code' => 'module_online',
                    'name' => 'Khóa học trực tuyến',
                    'unit_permission' => 0
                ]
            ]
        );

        DB::table('el_permission')->insert(
            [
                [
                    'code' => 'module.online.management',
                    'name' => 'Xem',
                    'parent_code' => 'module_online',
                    'extend' => null
                ],
                [
                    'code' => 'module.online.create',
                    'name' => 'Tạo mới',
                    'parent_code' => 'module_online',
                    'extend' => null
                ],
                [
                    'code' => 'module.online.edit',
                    'name' => 'Chỉnh sửa',
                    'parent_code' => 'module_online',
                    'extend' => 'module.online.management, module.online.create'
                ],
                [
                    'code' => 'module.online.remove',
                    'name' => 'Xóa',
                    'parent_code' => 'module_online',
                    'extend' => null
                ],
                [
                    'code' => 'module.online.approve',
                    'name' => 'Duyệt',
                    'parent_code' => 'module_online',
                    'extend' => null
                ],
                [
                    'code' => 'module.online.open',
                    'name' => 'Bật/Tắt',
                    'parent_code' => 'module_online',
                    'extend' => null
                ],
                [
                    'code' => 'module.online.register',
                    'name' => 'Xem ghi danh',
                    'parent_code' => 'module_online',
                    'extend' => null
                ],
                [
                    'code' => 'module.online.register.create',
                    'name' => 'Thêm ghi danh',
                    'parent_code' => 'module_online',
                    'extend' => null
                ],
                [
                    'code' => 'module.online.register.approve',
                    'name' => 'Duyệt ghi danh',
                    'parent_code' => 'module_online',
                    'extend' => 'module.training_unit.online.register.approve'
                ],
                [
                    'code' => 'module.online.register.remove',
                    'name' => 'Xoá ghi danh',
                    'parent_code' => 'module_online',
                    'extend' => null
                ]
            ]
        );*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
