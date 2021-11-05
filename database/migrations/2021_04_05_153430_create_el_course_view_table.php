<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElCourseViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_course_view', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id');
            $table->tinyInteger('course_type')->comment('1: online, 2: offline');
            $table->string('code', 150);
            $table->string('name');
            $table->integer('auto')->default(0)->comment('1: tự động duyệt, 0: duyệt tay');
            $table->string('unit_id')->nullable()->comment('Mã Đơn vị tạo khóa học');
            $table->integer('moodlecourseid')->index()->nullable();
            $table->integer('in_plan')->nullable()->comment('Trong kế hoạch');
            $table->integer('training_form_id')->nullable()->comment('Mã hình thức đào tạo');
            $table->string('training_form_name')->nullable()->comment('Hình thức đào tạo');
            $table->integer('plan_detail_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('isopen')->default(0);
            $table->text('tutorial')->nullable();
            $table->text('type_tutorial')->nullable();
            $table->integer('status')->default(2);
            $table->dateTime('start_date')->index()->nullable();
            $table->dateTime('end_date')->index()->nullable();
            $table->dateTime('register_deadline')->nullable()->comment('Hạn đăng ký');
            $table->string('image')->nullable();
            $table->integer('max_student')->default(0);
            $table->string('document')->nullable();
            $table->integer('created_by')->nullable()->default(2);
            $table->integer('updated_by')->nullable()->default(2);
            $table->integer('training_program_id')->index()->unsigned();
            $table->string('training_program_code')->nullable()->comment('Mã chủ đề');
            $table->string('training_program_name')->nullable()->comment('Chủ đề');
            $table->integer('level_subject_id')->unsigned()->nullable();
            $table->integer('subject_id')->index()->unsigned()->comment('Id chuyên đề');
            $table->string('subject_code')->nullable()->comment('Mã chuyên đề');
            $table->string('subject_name')->nullable()->comment('chuyên đề');
            $table->integer('training_location_id')->nullable()->default(0)->comment('Mã địa điểm đào tạo');
            $table->string('training_location_name',500)->nullable()->comment('địa điểm đào tạo');
            $table->string('training_unit',250)->nullable()->comment('Đơn vị đào tạo');
            $table->integer('training_partner_type')->nullable();
            $table->integer('training_unit_type')->nullable();
            $table->string('training_area_id')->nullable()->comment('Mã khu vực đào tạo');
            $table->string('training_area_name')->nullable()->comment('Khu vực đào tạo');
            $table->string('training_partner_id')->nullable()->comment('Mã đối tác');
            $table->string('training_partner_name')->nullable()->comment('Đối tác');
            $table->longText('content')->nullable();
            $table->integer('views')->default(0);
            $table->integer('category_id')->nullable();
            $table->string('course_time')->nullable()->comment('Thời lượng');
            $table->string('course_time_unit')->nullable();
            $table->integer('num_lesson')->nullable()->comment('Bài học');
            $table->integer('action_plan')->default(0)->comment('Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_template')->nullable()->comment('Id Mẫu Đánh giá hiệu quả đào tạo');
            $table->string('plan_app_template_name',500)->nullable()->comment('Mẫu Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_day')->nullable()->comment('Thời hạn đánh giá');
            $table->integer('cert_code')->nullable();
            $table->integer('has_cert')->nullable();
            $table->integer('teacher_id')->nullable()->comment('Giảng viên');
            $table->integer('rating')->nullable()->comment('Đánh giá sau khóa học');
            $table->integer('template_id')->nullable()->comment('Mẫu đánh giá');
            $table->string('template_name')->nullable()->comment('Mẫu đánh giá');
            $table->boolean('commit')->nullable()->index()->comment('Cam kết đào tạo');
            $table->date('commit_date')->nullable()->comment('Ngày bắt đầu tính cam kết');
            $table->float('coefficient',8,2)->nullable()->comment('Hệ số k');
            $table->decimal('cost_class',18,2)->nullable()->comment('Chi phí tổ chức');
            $table->integer('quiz_id')->nullable()->comment('Mã Kỳ thi');
            $table->string('quiz_name')->nullable()->comment('Tên Kỳ thi');
            $table->integer('unit_by')->nullable();
            $table->integer('max_grades')->default(0)->nullable();
            $table->integer('min_grades')->default(0)->nullable();
            $table->integer('course_employee')->default(0)->nullable()->comment('Khóa học dành cho');
            $table->string('course_employee_name')->nullable()->comment('tên khóa học dành cho');
            $table->integer('course_action')->default(0)->nullable()->comment('Mã Khóa học thực hiện');
            $table->string('course_action_name')->nullable()->comment('Khóa học thực hiện');
            $table->string('title_join_id')->nullable()->comment('Mã chức danh tham gia');
            $table->string('title_join_name')->nullable()->comment('Chức danh tham gia');
            $table->string('title_recommend_id')->nullable()->comment('Mã chức danh khuyến khích');
            $table->string('title_recommend_name')->nullable()->comment('Tên chức danh khuyến khích');
            $table->string('training_object_id')->nullable()->comment('Mã nhóm đối tượng tham gia');
            $table->string('training_object_name')->nullable()->comment('Nhóm đối tượng tham gia');
            $table->integer('teacher_type_id')->default(0)->nullable()->comment('Mã loại giảng viên');
            $table->string('teacher_type_name')->nullable()->comment('loại giảng viên');
            $table->integer('training_type_id')->default(0)->nullable()->comment('Mã hình thức đào tạo');
            $table->string('training_type_name')->nullable()->comment('Tên hình thức đào tạo');
            $table->integer('lock_course')->default(0);
            $table->integer('has_change')->default(0)->comment('ghi nhận thay đổi');
            $table->string('schedules')->nullable()->comment('Buổi học');
            $table->bigInteger('plan_amount')->nullable()->comment('Chi phí tạm tính của khóa học');
            $table->bigInteger('actual_amount')->nullable()->comment('chi phí thực chi của khóa học');
            $table->integer('expire_commit')->default(0)->index()->comment('Khóa có cam kết đã hết hạn ghi nhận 1 để khi chạy cron loại bỏ những khóa này');
            $table->integer('is_limit_time')->default(0)->nullable()->comment('giới hạn thời gian học');
            $table->string('start_timeday')->default('')->nullable();
            $table->string('end_timeday')->default('')->nullable();
            $table->tinyInteger('is_roadmap')->default(0)->comment('Khóa học trong tháp đào tạo');
            $table->string('approved_step')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_course_view');
    }
}
