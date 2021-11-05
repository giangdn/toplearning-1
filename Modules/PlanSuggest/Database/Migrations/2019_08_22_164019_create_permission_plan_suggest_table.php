<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionPlanSuggestTable extends Migration
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
                    'code' => 'module_plan_suggest',
                    'name' => 'Đề xuất kế hoạch đơn vị',
                    'unit_permission' => 1
                ]
            ]
        );
        DB::table('el_permission')->insert([
            [
                'code' => 'module.plan_suggest.approved',
                'name' => 'Duyệt đề xuất kế hoạch đào tạo đơn vị',
                'parent_code' => 'module_plan_suggest',
                'unit_permission' => 1,
                'extend' => null
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
        Schema::dropIfExists('permission_plan_suggest');
    }
}
