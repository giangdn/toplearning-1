<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionNotifyTable extends Migration
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
                    'code' => 'module_notify',
                    'name' => 'Thông báo',
                    'unit_permission' => 0
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.notify_send',
                'name' => 'Xem thông báo',
                'parent_code' => 'module_notify',
                'extend' => null,
            ],
            [
                'code' => 'module.notify_send.create',
                'name' => 'Thêm thông báo',
                'parent_code' => 'module_notify',
                'extend' => null,
            ],
            [
                'code' => 'module.notify_send.edit',
                'name' => 'Chỉnh sửa thông báo',
                'parent_code' => 'module_notify',
                'extend' => 'module.notify_send, module.notify_send.create',
            ],
            [
                'code' => 'module.notify_send.remove',
                'name' => 'Xoá thông báo',
                'parent_code' => 'module_notify',
                'extend' => null,
            ],
            [
                'code' => 'module.notify_send.ajax_isopen_publish',
                'name' => 'Bật/Tắt thông báo',
                'parent_code' => 'module_notify',
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
        Schema::dropIfExists('permission_notify');
    }
}
