<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionCategoryTable extends Migration
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
                    'code' => 'backend_category',
                    'name' => 'Danh mục',
                    'unit_permission' => 0
                ]
            ]
        );
        DB::table('el_permission')->insert([
            [
                'code' => 'backend.category.unit',
                'name' => 'Quản lý đơn vị',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.unit_type',
                'name' => 'Quản lý loại đơn vị',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.titles',
                'name' => 'Quản lý chức danh',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.training_program',
                'name' => 'Quản lý chương trình đào tạo',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.subject',
                'name' => 'Quản lý học phần',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.subject_conditions',
                'name' => 'Điều kiện học phần',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.training_location',
                'name' => 'Quản lý địa điểm đào tạo',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.course_categories',
                'name' => 'Quản lý danh mục khóa học',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.training_partner',
                'name' => 'Quản lý danh mục đối tác',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.training_form',
                'name' => 'Quản lý danh mục hình thức đào tạo',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.training_cost',
                'name' => 'Quản lý chi phí đào tạo',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.student_cost',
                'name' => 'Quản lý chi phí học viên',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.cost_lessons',
                'name' => 'Quản lý chi phí tiết giảng',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.training_teacher',
                'name' => 'Quản lý giảng viên',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.teacher_type',
                'name' => 'Quản lý loại giảng viên',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.province',
                'name' => 'Quản lý tỉnh thành',
                'parent_code' => 'backend_category',
                'extend' => null,
            ],
            [
                'code' => 'backend.category.district',
                'name' => 'Quản lý quận huyện',
                'parent_code' => 'backend_category',
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
        Schema::dropIfExists('permission_category');
    }
}
