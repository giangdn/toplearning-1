<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionSuggestTable extends Migration
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
                    'code' => 'module_suggest',
                    'name' => 'Quản lý đề xuất',
                    'unit_permission' => 0
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.suggest',
                'name' => 'Xem / Bình luận đề xuất',
                'parent_code' => 'module_suggest',
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
        Schema::dropIfExists('permission_suggest');
    }
}
