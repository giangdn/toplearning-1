<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElPermissionReportTable extends Migration
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
                    'code' => 'module_report',
                    'name' => 'Xuất báo cáo',
                    'unit_permission' => 0
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.report.BC01',
                'name' => 'Báo cáo 01',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC02',
                'name' => 'Báo cáo 02',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC03',
                'name' => 'Báo cáo 03',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC04',
                'name' => 'Báo cáo 04',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC05',
                'name' => 'Báo cáo 05',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC06',
                'name' => 'Báo cáo 06',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC07',
                'name' => 'Báo cáo 07',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC08',
                'name' => 'Báo cáo 08',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC09',
                'name' => 'Báo cáo 09',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC10',
                'name' => 'Báo cáo 10',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC11',
                'name' => 'Báo cáo 11',
                'parent_code' => 'module_report',
                'extend' => null,
            ],[
                'code' => 'module.report.BC12',
                'name' => 'Báo cáo 12',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC13',
                'name' => 'Báo cáo 13',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC14',
                'name' => 'Báo cáo 14',
                'parent_code' => 'module_report',
                'extend' => null,
            ],[
                'code' => 'module.report.BC15',
                'name' => 'Báo cáo 15',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC16',
                'name' => 'Báo cáo 16',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC17',
                'name' => 'Báo cáo 17',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC18',
                'name' => 'Báo cáo 18',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC19',
                'name' => 'Báo cáo 19',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC20',
                'name' => 'Báo cáo 20',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC21',
                'name' => 'Báo cáo 21',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC22',
                'name' => 'Báo cáo 22',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC23',
                'name' => 'Báo cáo 23',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC24',
                'name' => 'Báo cáo 24',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC25',
                'name' => 'Báo cáo 25',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC26',
                'name' => 'Báo cáo 26',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC27',
                'name' => 'Báo cáo 27',
                'parent_code' => 'module_report',
                'extend' => null,
            ],
            [
                'code' => 'module.report.BC28',
                'name' => 'Báo cáo 28',
                'parent_code' => 'module_report',
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
        Schema::dropIfExists('el_permission_report');
    }
}
