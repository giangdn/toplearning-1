<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionRatingTable extends Migration
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
                    'code' => 'module_rating',
                    'name' => 'Mẫu đánh giá sau khóa học',
                    'unit_permission' => 0
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.rating.template',
                'name' => 'Xem mẫu đánh giá',
                'parent_code' => 'module_rating',
                'extend' => null,
            ],
            [
                'code' => 'module.rating.template.create',
                'name' => 'Thêm mẫu đánh giá',
                'parent_code' => 'module_rating',
                'extend' => null,
            ],
            [
                'code' => 'module.rating.template.edit',
                'name' => 'Chỉnh sửa mẫu đánh giá',
                'parent_code' => 'module_rating',
                'extend' => 'module.rating.template, module.rating.template.create',
            ],
            [
                'code' => 'module.rating.template.remove',
                'name' => 'Xoá mẫu đánh giá',
                'parent_code' => 'module_rating',
                'extend' => null,
            ],
            [
                'code' => 'module.rating.result.index',
                'name' => 'Xem kết quả đánh giá',
                'parent_code' => 'module_rating',
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
        Schema::dropIfExists('permission_rating');
    }
}
