<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionSliderTable extends Migration
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
                    'code' => 'backend_slider',
                    'name' => 'Quản lý slider',
                    'unit_permission' => 0
                ]
            ]
        );
        DB::table('el_permission')->insert([
            [
                'code' => 'backend.slider',
                'name' => 'Xem slider',
                'parent_code' => 'backend_slider',
                'extend' => null,
            ],
            [
                'code' => 'backend.slider.create',
                'name' => 'Thêm slider',
                'parent_code' => 'backend_slider',
                'extend' => null,
            ],
            [
                'code' => 'backend.slider.edit',
                'name' => 'Chỉnh sửa slider',
                'parent_code' => 'backend_slider',
                'extend' => 'backend.slider, backend.slider.create',
            ],
            [
                'code' => 'backend.slider.remove',
                'name' => 'Xoá slider',
                'parent_code' => 'backend_slider',
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
        Schema::dropIfExists('permission_slider');
    }
}
