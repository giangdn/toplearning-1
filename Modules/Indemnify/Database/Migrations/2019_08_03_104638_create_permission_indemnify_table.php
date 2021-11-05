<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionIndemnifyTable extends Migration
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
                    'code' => 'module_indemnify',
                    'name' => 'Quản lý bồi hoàn',
                    'unit_permission' => 0
                ]
            ]
        );
        DB::table('el_permission')->insert([
            [
                'code' => 'module.indemnify',
                'name' => 'Xem quản lý bồi hoàn',
                'parent_code' => 'module_indemnify',
                'extend' => null,
            ],
            [
                'code' => 'module.indemnify.user',
                'name' => 'Chỉnh sửa số ngày cam kết',
                'parent_code' => 'module_indemnify',
                'extend' => 'module.indemnify',
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
        Schema::dropIfExists('permission_indemnify');
    }
}
