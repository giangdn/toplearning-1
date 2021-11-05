<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionQuizTable extends Migration
{
    public function up()
    {
        /*DB::table('el_permission')->insert(
            [
                [
                    'code' => 'module_quiz',
                    'name' => 'Các kỳ thi',
                    'unit_permission' => 0
                ]
            ]
        );

        DB::table('el_permission')->insert([
            [
                'code' => 'module.quiz.manager',
                'name' => 'Xem kỳ thi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.create',
                'name' => 'Thêm kỳ thi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.edit',
                'name' => 'Chỉnh sửa kỳ thi',
                'parent_code' => 'module_quiz',
                'extend' => 'module.quiz.manager, module.quiz.create, module.quiz.question, module.quiz.register, module.quiz.register.user_secondary, module.quiz.result',
            ],
            [
                'code' => 'module.quiz.remove',
                'name' => 'Xoá kỳ thi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.ajax_view_result',
                'name' => 'Cho / Tắt xem kết quả',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.ajax_status',
                'name' => 'Duyệt / Từ chối kỳ thi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.ajax_is_open',
                'name' => 'Bật / Tắt kỳ thi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.ajax_copy_quiz',
                'name' => 'Copy kỳ thi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.question',
                'name' => 'Thêm câu hỏi trong kỳ thi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.register',
                'name' => 'Thêm người thi trong',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.register.user_secondary',
                'name' => 'Thêm người thi ngoài',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.result',
                'name' => 'Xem kết quả',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.result.save_grade',
                'name' => 'Sửa điểm thi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.result.save_reexamine',
                'name' => 'Sửa điểm phúc khảo',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.result.export_result',
                'name' => 'Xuất file kết quả',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.export_quiz',
                'name' => 'In đề thi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.quiz.questionlib',
                'name' => 'Xem danh mục ngân hàng câu hỏi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.questionlib.get_modal.create',
                'name' => 'Thêm danh mục ngân hàng câu hỏi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.questionlib.get_modal.edit',
                'name' => 'Chỉnh sửa danh mục ngân hàng câu hỏi',
                'parent_code' => 'module_quiz',
                'extend' => 'module.quiz.questionlib, module.quiz.questionlib.get_modal.create',
            ],
            [
                'code' => 'module.quiz.questionlib.remove_category',
                'name' => 'Xoá danh mục ngân hàng câu hỏi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.questionlib.cate_user',
                'name' => 'Phân quyền danh mục ngân hàng câu hỏi',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],

        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.quiz.questionlib.question',
                'name' => 'Xem câu hỏi trong danh mục',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.questionlib.question.create',
                'name' => 'Thêm câu hỏi trong danh mục',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.questionlib.question.edit',
                'name' => 'Chỉnh sửa câu hỏi trong danh mục',
                'parent_code' => 'module_quiz',
                'extend' => 'module.quiz.questionlib.question, module.quiz.questionlib.question.create',
            ],
            [
                'code' => 'module.quiz.questionlib.remove_question',
                'name' => 'Xoá câu hỏi trong danh mục',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.questionlib.ajax_status',
                'name' => 'Duyệt / Từ chối câu hỏi trong danh mục',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
        ]);

        DB::table('el_permission')->insert([
            [
                'code' => 'module.quiz.user_secondary',
                'name' => 'Xem người thi ngoài',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.user_secondary.create',
                'name' => 'Thêm người thi ngoài',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
            [
                'code' => 'module.quiz.user_secondary.edit',
                'name' => 'Chỉnh sửa người thi ngoài',
                'parent_code' => 'module_quiz',
                'extend' => 'module.quiz.user_secondary, module.quiz.user_secondary.create',
            ],
            [
                'code' => 'module.quiz.user_secondary.remove',
                'name' => 'Xoá người thi ngoài',
                'parent_code' => 'module_quiz',
                'extend' => null,
            ],
        ]);*/
    }
    
    public function down()
    {
        //Schema::dropIfExists('permission_quiz');
    }
}
