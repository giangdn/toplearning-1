<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionFeedbackTable extends Migration
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
                    'code' => 'backend_feedback',
                    'name' => 'Quản lý phản hồi',
                    'unit_permission' => 0
                ]
            ]
        );
        DB::table('el_permission')->insert([
            [
                'code' => 'backend.feedback',
                'name' => 'Xem phản hồi',
                'parent_code' => 'backend_feedback',
                'extend' => null,
            ],
            [
                'code' => 'backend.feedback.create',
                'name' => 'Thêm phản hồi',
                'parent_code' => 'backend_feedback',
                'extend' => null,
            ],
            [
                'code' => 'backend.feedback.edit',
                'name' => 'Chỉnh sửa phản hồi',
                'parent_code' => 'backend_feedback',
                'extend' => 'backend.feedback, backend.feedback.create',
            ],
            [
                'code' => 'backend.feedback.remove',
                'name' => 'Xoá phản hồi',
                'parent_code' => 'backend_feedback',
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
        Schema::dropIfExists('permission_feedback');
    }
}
