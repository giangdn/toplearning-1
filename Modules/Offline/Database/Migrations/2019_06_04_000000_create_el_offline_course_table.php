<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOfflineCourseTable extends Migration
{
    public function up()
    {
        Schema::create('el_offline_course', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->string('unit_id')->nullable()->comment('Đơn vị tạo khóa học');
            $table->bigInteger('in_plan')->nullable()->comment('Trong kế hoạch');
            $table->bigInteger('training_form_id')->nullable()->comment('Loại hình đào tạo');
            $table->integer('plan_detail_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('isopen')->default(0);
            $table->integer('status')->default(2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('register_deadline')->nullable();
            $table->string('image')->nullable();
            $table->integer('max_student')->default(0);
            $table->text('document')->nullable();
            $table->bigInteger('created_by')->default(2);
            $table->bigInteger('updated_by')->default(2);
            $table->bigInteger('training_program_id')->index()->unsigned();
            $table->bigInteger('level_subject_id')->unsigned()->nullable();
            $table->bigInteger('subject_id')->index()->unsigned();
            $table->bigInteger('training_location_id')->nullable()->default(0);
            $table->string('training_unit',250)->nullable()->comment('Đơn vị đào tạo');
            $table->integer('training_partner_type')->nullable();
            $table->integer('training_unit_type')->nullable();
            $table->string('training_area_id')->nullable();
            $table->string('training_partner_id')->nullable();
            $table->longText('content')->nullable();
            $table->integer('views')->default(0);
            $table->integer('category_id')->nullable();
            $table->string('course_time')->nullable()->comment('Thời lượng');
            $table->string('course_time_unit')->nullable();
            $table->integer('num_lesson')->nullable();
            $table->integer('action_plan')->default(0)->comment('Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_template')->nullable()->comment('Mẫu Đánh giá hiệu quả đào tạo');
            $table->integer('plan_app_day')->nullable()->comment('Thời hạn đánh giá');
            $table->integer('cert_code')->nullable();
            $table->integer('has_cert')->nullable();
            $table->bigInteger('teacher_id')->nullable()->comment('Giảng viên');
            $table->integer('rating')->nullable()->comment('Đánh giá sau khóa học');
            $table->bigInteger('template_id')->nullable()->comment('Mẫu đánh giá');
            $table->boolean('commit')->nullable()->comment('Cam kết đào tạo');
            $table->date('commit_date')->nullable()->comment('Ngày bắt đầu tính cam kết');
            $table->float('coefficient',8,2)->nullable()->comment('Hệ số k');
            $table->decimal('cost_class',18,2)->nullable()->comment('Chi phí tổ chức');
            $table->integer('quiz_id')->nullable()->comment('Kỳ thi');
            $table->integer('unit_by')->nullable();
            $table->integer('max_grades')->default(0)->nullable();
			$table->integer('min_grades')->default(0)->nullable();
			$table->integer('course_employee')->default(0)->nullable();
			$table->integer('course_action')->default(0)->nullable();
			$table->string('title_join_id')->nullable();
			$table->string('title_recommend_id')->nullable();
			$table->string('training_object_id')->nullable();
			$table->integer('teacher_type_id')->default(0)->nullable();
			$table->integer('training_type_id')->default(0)->nullable();
			$table->integer('lock_course')->default(0);
			$table->integer('enter_student_cost')->default(0);
			$table->dateTime('rating_end_date')->nullable();
            $table->string('approved_step')->nullable()->comment('step phê duyệt');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('el_offline_course');
    }
}
