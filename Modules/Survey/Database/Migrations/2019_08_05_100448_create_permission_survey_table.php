<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionSurveyTable extends Migration
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
                    'code' => 'module_survey',
                    'name' => 'Quản lý khảo sát',
                    'unit_permission' => 0
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.survey.template',
                'name' => 'Xem mẫu khảo sát',
                'parent_code' => 'module_survey',
                'extend' => null,
            ],
            [
                'code' => 'module.survey.template.create',
                'name' => 'Thêm mẫu khảo sát',
                'parent_code' => 'module_survey',
                'extend' => null,
            ],
            [
                'code' => 'module.survey.template.edit',
                'name' => 'Chỉnh sửa mẫu khảo sát',
                'parent_code' => 'module_survey',
                'extend' => 'module.survey.template, module.survey.template.create',
            ],
            [
                'code' => 'module.survey.template.remove',
                'name' => 'Xoá mẫu khảo sát',
                'parent_code' => 'module_survey',
                'extend' => null,
            ],
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.survey.index',
                'name' => 'Xem khảo sát',
                'parent_code' => 'module_survey',
                'extend' => null,
            ],
            [
                'code' => 'module.survey.create',
                'name' => 'Thêm khảo sát',
                'parent_code' => 'module_survey',
                'extend' => null,
            ],
            [
                'code' => 'module.survey.edit',
                'name' => 'Chỉnh sửa khảo sát',
                'parent_code' => 'module_survey',
                'extend' => 'module.survey.index, module.survey.create',
            ],
            [
                'code' => 'module.survey.remove',
                'name' => 'Xoá khảo sát',
                'parent_code' => 'module_survey',
                'extend' => null,
            ],
            [
                'code' => 'module.survey.ajax_isopen_publish',
                'name' => 'Bật / Tắt khảo sát',
                'parent_code' => 'module_survey',
                'extend' => null,
            ],
            [
                'code' => 'module.survey.report.index',
                'name' => 'Xem báo cáo chi tiết',
                'parent_code' => 'module_survey',
                'extend' => null,
            ],
            [
                'code' => 'module.survey.report.export',
                'name' => 'Xuất báo cáo tổng hợp',
                'parent_code' => 'module_survey',
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
        Schema::dropIfExists('permission_survey');
    }
}
