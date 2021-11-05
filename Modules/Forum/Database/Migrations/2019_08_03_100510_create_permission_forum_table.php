<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionForumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       /* DB::table('el_permission')->insert(
            [
                [
                    'code' => 'module_forum',
                    'name' => 'Diễn đàn',
                    'unit_permission' => 0
                ]
            ]
        );
        DB::table('el_permission')->insert([
            [
                'code' => 'module.forum',
                'name' => 'Xem diễn đàn',
                'parent_code' => 'module_forum',
                'extend' => null,
            ],
            [
                'code' => 'module.forum.create',
                'name' => 'Thêm mới diễn đàn',
                'parent_code' => 'module_forum',
                'extend' => null,
            ],
            [
                'code' => 'module.forum.edit',
                'name' => 'Chỉnh sửa diễn đàn',
                'parent_code' => 'module_forum',
                'extend' => 'module.forum, module.forum.create',
            ],
            [
                'code' => 'module.forum.remove',
                'name' => 'Xoá diễn đàn',
                'parent_code' => 'module_forum',
                'extend' => null,
            ],
            [
                'code' => 'module.forum.threat',
                'name' => 'Duyệt bài đăng',
                'parent_code' => 'module_forum',
                'extend' => 'module.forum',
            ],
            [
                'code' => 'module.frontend.forums.formsave',
                'name' => 'Gửi bài viết',
                'parent_code' => 'module_forum',
                'extend' => null,
            ],
            [
                'code' => 'module.frontend.forums.deleteforum',
                'name' => 'Xoá bài viết',
                'parent_code' => 'module_forum',
                'extend' => null,
            ],
            [
                'code' => 'module.frontend.forums.delete',
                'name' => 'Xoá bình luận',
                'parent_code' => 'module_forum',
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
        Schema::dropIfExists('permission_forum');
    }
}
