<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionLibrariesTable extends Migration
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
                    'code' => 'module_libraries',
                    'name' => 'Thư viện',
                    'unit_permission' => 0
                ]
            ]
        );
        //danh mục thư viện
        DB::table('el_permission')->insert([
                        [
                'code' => 'module.libraries.category',
                'name' => 'Xem danh mục thư viện',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
            [
                'code' => 'module.libraries.category.create',
                'name' => 'Thêm danh mục thư viện',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
            [
                'code' => 'module.libraries.category.edit',
                'name' => 'Chỉnh sửa danh mục thư viện',
                'parent_code' => 'module_libraries',
                'extend' => 'module.libraries.category, module.libraries.category.create',
            ],
            [
                'code' => 'module.libraries.category.remove',
                'name' => 'Xoá danh mục thư viện',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
        ]);

        //sách
        DB::table('el_permission')->insert([
            [
                'code' => 'module.libraries.book.register',
                'name' => 'Quản lý mượn sách',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
            [
                'code' => 'module.libraries.book',
                'name' => 'Xem sách',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
            [
                'code' => 'module.libraries.book.create',
                'name' => 'Thêm sách',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
            [
                'code' => 'module.libraries.book.edit',
                'name' => 'Chỉnh sửa sách',
                'parent_code' => 'module_libraries',
                'extend' => 'module.libraries.book, module.libraries.book.create',
            ],
            [
                'code' => 'module.libraries.book.remove',
                'name' => 'Xoá sách',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
        ]);

        //ebook
        DB::table('el_permission')->insert([
            [
                'code' => 'module.libraries.ebook',
                'name' => 'Xem sách điện tử',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
            [
                'code' => 'module.libraries.ebook.create',
                'name' => 'Thêm sách điện tử',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
            [
                'code' => 'module.libraries.ebook.edit',
                'name' => 'Chỉnh sửa sách điện tử',
                'parent_code' => 'module_libraries',
                'extend' => 'module.libraries.ebook, module.libraries.ebook.create',
            ],
            [
                'code' => 'module.libraries.ebook.remove',
                'name' => 'Xoá sách điện tử',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
        ]);

        //tài liệu
        DB::table('el_permission')->insert([
            [
                'code' => 'module.libraries.document',
                'name' => 'Xem tài liệu',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
            [
                'code' => 'module.libraries.document.create',
                'name' => 'Thêm tài liệu',
                'parent_code' => 'module_libraries',
                'extend' => null,
            ],
            [
                'code' => 'module.libraries.document.edit',
                'name' => 'Chỉnh sửa tài liệu',
                'parent_code' => 'module_libraries',
                'extend' => 'module.libraries.document, module.libraries.document.create',
            ],
            [
                'code' => 'module.libraries.document.remove',
                'name' => 'Xoá tài liệu',
                'parent_code' => 'module_libraries',
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
        Schema::dropIfExists('permission_libraries');
    }
}
