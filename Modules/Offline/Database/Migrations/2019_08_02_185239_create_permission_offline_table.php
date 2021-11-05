<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionOfflineTable extends Migration
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
                    'code' => 'module_offline',
                    'name' => 'Khóa học tập trung',
                    'unit_permission' => 0
                ]
            ]
        );
        DB::table('el_permission')->insert(
            [
                [
                    'code' => 'module.offline.management',
                    'name' => 'Xem',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.create',
                    'name' => 'Tạo mới',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.edit',
                    'name' => 'Chỉnh sửa',
                    'parent_code' => 'module_offline',
                    'extend' => 'module.offline.management, module.offline.create, module.offline.teacher, module.offline.save_teacher, module.offline.remove_teacher, module.offline.attendance, module.offline.result, module.offline.save_score, module.rating.result.index',
                ],
                [
                    'code' => 'module.offline.remove',
                    'name' => 'Xóa',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.approve',
                    'name' => 'Duyệt',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.open',
                    'name' => 'Bật/Tắt',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.register',
                    'name' => 'Xem ghi danh',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.register.create',
                    'name' => 'Thêm ghi danh',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.register.approve',
                    'name' => 'Duyệt ghi danh',
                    'parent_code' => 'module_offline',
                    'extend' => 'module.training_unit.offline.register.approve'
                ],
                [
                    'code' => 'module.offline.register.remove',
                    'name' => 'Xoá ghi danh',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.teacher',
                    'name' => 'Xem giảng viên',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.save_teacher',
                    'name' => 'Thêm giảng viên',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.remove_teacher',
                    'name' => 'Xoá giảng viên',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.attendance',
                    'name' => 'Điểm danh',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.result',
                    'name' => 'Xem kết quả đào tạo',
                    'parent_code' => 'module_offline',
                    'extend' => null
                ],
                [
                    'code' => 'module.offline.save_score',
                    'name' => 'Nhập kết quả đào tạo',
                    'parent_code' => 'module_offline',
                    'extend' => 'module.training_unit.offline.edit'
                ],
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
        Schema::dropIfExists('permission_offline');
    }
}
