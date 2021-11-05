<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTrainingUnit extends Migration
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
                    'code' => 'module.training_unit',
                    'name' => 'Đào tạo đơn vị',
                    'unit_permission' => 1
                ]
            ]
        );

        DB::table('el_permission')->insert(
            [
//                [
//                    'code' => 'module.training_unit.online',
//                    'name' => 'Xem khóa học trực tuyến',
//                    'parent_code' => 'module.training_unit',
//                    'unit_permission' => 1,
//                    'extend' => null
//                ],
//                [
//                    'code' => 'module.training_unit.online.create',
//                    'name' => 'Thêm khóa học trực tuyến',
//                    'parent_code' => 'module.training_unit',
//                    'unit_permission' => 1,
//                    'extend' => null
//                ],
//                [
//                    'code' => 'module.training_unit.online.edit',
//                    'name' => 'Sửa khóa học trực tuyến',
//                    'parent_code' => 'module.training_unit',
//                    'unit_permission' => 1,
//                    'extend' => 'module.training_unit.online, module.training_unit.online.create'
//                ],
//                [
//                    'code' => 'module.training_unit.online.remove',
//                    'name' => 'Xóa khóa học trực tuyến',
//                    'parent_code' => 'module.training_unit',
//                    'unit_permission' => 1,
//                    'extend' => null
//                ],
//                [
//                    'code' => 'module.training_unit.online.approve',
//                    'name' => 'Duyệt khóa học trực tuyến',
//                    'parent_code' => 'module.training_unit',
//                    'unit_permission' => 1,
//                    'extend' => null
//                ],
//                [
//                    'code' => 'module.training_unit.online.open',
//                    'name' => 'Bật/tắt khóa học trực tuyến',
//                    'parent_code' => 'module.training_unit',
//                    'unit_permission' => 1,
//                    'extend' => null
//                ],
//                [
//                    'code' => 'module.training_unit.online.register',
//                    'name' => 'Xem ghi danh khóa học trực tuyến',
//                    'parent_code' => 'module.training_unit',
//                    'unit_permission' => 1,
//                    'extend' => null
//                ],
//                [
//                    'code' => 'module.training_unit.online.register.create',
//                    'name' => 'Thêm ghi danh khóa học trực tuyến',
//                    'parent_code' => 'module.training_unit',
//                    'unit_permission' => 1,
//                    'extend' => null
//                ],
//                [
//                    'code' => 'module.training_unit.online.register.approve',
//                    'name' => 'Duyệt ghi danh khóa học trực tuyến',
//                    'parent_code' => 'module.training_unit',
//                    'unit_permission' => 1,
//                    'extend' => null
//                ],
                [
                    'code' => 'module.training_unit.offline',
                    'name' => 'Xem khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.offline.create',
                    'name' => 'Tạo khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.offline.edit',
                    'name' => 'Sửa khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => 'module.training_unit.offline, module.training_unit.offline.create'
                ],
                [
                    'code' => 'module.training_unit.offline.remove',
                    'name' => 'Xóa khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.offline.approve',
                    'name' => 'Duyệt khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.offline.open',
                    'name' => 'Bật/tắt khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.offline.register',
                    'name' => 'Xem ghi danh khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.offline.register.approve',
                    'name' => 'Duyệt ghi danh khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.offline.register.create',
                    'name' => 'Thêm ghi danh khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.offline.attendance',
                    'name' => 'Điểm danh khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.offline.result',
                    'name' => 'Nhập điểm khóa học tập trung',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz',
                    'name' => 'Xem kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.create',
                    'name' => 'Tạo kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.edit',
                    'name' => 'Sửa kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => 'module.training_unit.quiz, module.training_unit.quiz.create'
                ],
                [
                    'code' => 'module.training_unit.quiz.remove',
                    'name' => 'Xoá kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.ajax_result',
                    'name' => 'Cho / Tắt xem kết quả',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.approve',
                    'name' => 'Duyệt kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.open',
                    'name' => 'Bật/tắt kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.copy',
                    'name' => 'Copy kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.question',
                    'name' => 'Thêm câu hỏi vào kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.register',
                    'name' => 'Xem ghi danh kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.register.user_secondary',
                    'name' => 'Xem người thi ngoài kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.result',
                    'name' => 'Xem kết quả kỳ thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null
                ],
                [
                    'code' => 'module.training_unit.quiz.result.save_grade',
                    'name' => 'Sửa điểm thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null,
                ],
                [
                    'code' => 'module.training_unit.quiz.result.save_reexamine',
                    'name' => 'Sửa điểm phúc khảo',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null,
                ],
                [
                    'code' => 'module.training_unit.quiz.result.export_result',
                    'name' => 'Xuất file kết quả',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null,
                ],
                [
                    'code' => 'module.training_unit.quiz.export_quiz',
                    'name' => 'In đề thi',
                    'parent_code' => 'module.training_unit',
                    'unit_permission' => 1,
                    'extend' => null,
                ],
            ]
        );*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
