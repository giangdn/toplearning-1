<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionUserTable extends Migration
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
                    'code' => 'module_user',
                    'name' => 'Quản lý người dùng',
                    'unit_permission' => 1
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.backend.user',
                'name' => 'Xem quản lý người dùng',
                'parent_code' => 'module_user',
                'extend' => null,
            ],
            [
                'code' => 'module.backend.user.create',
                'name' => 'Thêm quản lý người dùng',
                'parent_code' => 'module_user',
                'extend' => null,
            ],
            [
                'code' => 'module.backend.user.edit',
                'name' => 'Chỉnh sửa quản lý người dùng',
                'parent_code' => 'module_user',
                'extend' => 'module.backend.user, module.backend.user.create, module.backend.user.trainingprocess, module.backend.user.quizresult, module.backend.user.roadmap',
            ],
            [
                'code' => 'module.backend.user.remove',
                'name' => 'Xoá quản lý người dùng',
                'parent_code' => 'module_user',
                'extend' => null,
            ],
            [
                'code' => 'module.backend.user.trainingprocess',
                'name' => 'Xem quá trình đào tạo',
                'parent_code' => 'module_user',
                'extend' => null,
            ],
            [
                'code' => 'module.backend.user.quizresult',
                'name' => 'Xem kết quả thi',
                'parent_code' => 'module_user',
                'extend' => null,
            ],
            [
                'code' => 'module.backend.user.roadmap',
                'name' => 'Xem chương trình khung',
                'parent_code' => 'module_user',
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
        Schema::dropIfExists('permission_user');
    }
}
