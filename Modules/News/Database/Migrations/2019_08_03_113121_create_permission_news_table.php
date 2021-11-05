<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionNewsTable extends Migration
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
                    'code' => 'module_news',
                    'name' => 'Tin tức',
                    'unit_permission' => 0
                ]
            ]
        );
        DB::table('el_permission')->insert([
            [
                'code' => 'module.news.category',
                'name' => 'Xem danh mục tin tức',
                'parent_code' => 'module_news',
                'extend' => null,
            ],
            [
                'code' => 'module.news.category.create',
                'name' => 'Thêm danh mục tin tức',
                'parent_code' => 'module_news',
                'extend' => null,
            ],
            [
                'code' => 'module.news.category.edit',
                'name' => 'Chỉnh sửa danh mục tin tức',
                'parent_code' => 'module_news',
                'extend' => 'module.news.category, module.news.category.create',
            ],
            [
                'code' => 'module.news.category.remove',
                'name' => 'Xoá danh mục tin tức',
                'parent_code' => 'module_news',
                'extend' => null,
            ],
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.news.manager',
                'name' => 'Xem tin tức',
                'parent_code' => 'module_news',
                'extend' => null,
            ],
            [
                'code' => 'module.news.create',
                'name' => 'Thêm tin tức',
                'parent_code' => 'module_news',
                'extend' => null,
            ],
            [
                'code' => 'module.news.edit',
                'name' => 'Chỉnh sửa tin tức',
                'parent_code' => 'module_news',
                'extend' => 'module.news.manager, module.news.create',
            ],
            [
                'code' => 'module.news.remove',
                'name' => 'Xoá tin tức',
                'parent_code' => 'module_news',
                'extend' => null,
            ],
            [
                'code' => 'module.news.ajax_isopen_publish',
                'name' => 'Bật/Tắt tin tức',
                'parent_code' => 'module_news',
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
        Schema::dropIfExists('permission_news');
    }
}
