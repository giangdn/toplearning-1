<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionConvertTitlesTable extends Migration
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
                    'code' => 'module_convert_titles',
                    'name' => 'Chuyển đổi chức danh',
                    'unit_permission' => 1
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.convert_titles.list_unit',
                'name' => 'Đánh giá chuyển đổi chức danh',
                'parent_code' => 'module_convert_titles',
                'unit_permission' => 1,
                'extend' => null
            ]
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.convert_titles',
                'name' => 'Xem chuyển đổi chức danh',
                'parent_code' => 'module_convert_titles',
                'extend' => null
            ],
            [
                'code' => 'module.convert_titles.create',
                'name' => 'Thêm chuyển đổi chức danh',
                'parent_code' => 'module_convert_titles',
                'extend' => null
            ],
            [
                'code' => 'module.convert_titles.edit',
                'name' => 'Chỉnh sửa chuyển đổi chức danh',
                'parent_code' => 'module_convert_titles',
                'extend' => 'module.convert_titles, module.convert_titles.create'
            ],
            [
                'code' => 'module.convert_titles.remove',
                'name' => 'Xóa chuyển đổi chức danh',
                'parent_code' => 'module_convert_titles',
                'extend' => null
            ]
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.convert_titles.reviews',
                'name' => 'Xem mẫu đánh giá',
                'parent_code' => 'module_convert_titles',
                'extend' => null
            ],
            [
                'code' => 'module.convert_titles.reviews.create',
                'name' => 'Thêm mẫu đánh giá',
                'parent_code' => 'module_convert_titles',
                'extend' => null
            ],
            [
                'code' => 'module.convert_titles.reviews.edit',
                'name' => 'Chỉnh sửa mẫu đánh giá',
                'parent_code' => 'module_convert_titles',
                'extend' => 'module.convert_titles.reviews, module.convert_titles.reviews.create'
            ],
            [
                'code' => 'module.convert_titles.reviews.remove',
                'name' => 'Xóa mẫu đánh giá',
                'parent_code' => 'module_convert_titles',
                'extend' => null
            ]
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.convert_titles.roadmap',
                'name' => 'Xem chương trình khung',
                'parent_code' => 'module_convert_titles',
                'extend' => null,
            ],
            [
                'code' => 'module.convert_titles.roadmap.create',
                'name' => 'Thêm mới chương trình khung',
                'parent_code' => 'module_convert_titles',
                'extend' => null,
            ],
            [
                'code' => 'module.convert_titles.roadmap.edit',
                'name' => 'Chỉnh sửa chương trình khung',
                'parent_code' => 'module_convert_titles',
                'extend' => 'module.convert_titles.roadmap, module.convert_titles.roadmap.create',
            ],
            [
                'code' => 'module.convert_titles.roadmap.remove',
                'name' => 'Xoá chương trình khung',
                'parent_code' => 'module_convert_titles',
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
        Schema::dropIfExists('permission_convert_titles');
    }
}
