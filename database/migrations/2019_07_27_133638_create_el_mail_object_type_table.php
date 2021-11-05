<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElMailObjectTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_mail_object_type', function (Blueprint $table) {
            $table->string('code', 150)->primary();
            $table->string('name');
        });

        $this->insertData();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('el_mail_object_type');
    }

    public function insertData() {
        DB::table('el_mail_object_type')->insert([
            [
                'code' => 'registered_course',
                'name' => 'Thông báo đã được ghi danh khóa học',
            ],
            [
                'code' => 'register_course_online_object',
                'name' => 'Thông báo ghi danh khóa học online theo đối tượng',
            ],
            [
                'code' => 'online_reminder_01',
                'name' => 'Thông báo khóa học sắp kết thúc',
            ],
            [
                'code' => 'online_reminder_02',
                'name' => 'Thông báo đã lâu không tham gia khóa học',
            ],
            [
                'code' => 'online_completed',
                'name' => 'Thông báo hoàn thành khóa học online',
            ],
            [
                'code' => 'quiz_register',
                'name' => 'Thông báo đã được ghi danh kỳ thi',
            ],
            [
                'code' => 'quiz_result',
                'name' => 'Thông báo kết quả kỳ thi',
            ],
            [
                'code' => 'course_cance',
                'name' => 'Thông báo khóa học bị hủy',
            ],
            [
                'code' => 'declined_enroll_online',
                'name' => 'Thông báo bị từ chối ghi danh khóa học online',
            ],
            [
                'code' => 'delete_course_online',
                'name' => 'Thông báo khóa học online bị xóa',
            ],
            [
                'code' => 'action_plan_reminder_online_01',
                'name' => 'Thông báo lập Bảng đánh giá hiệu quả của Khóa học',
            ],
            [
                'code' => 'action_plan_reminder_online_02',
                'name' => 'Thông báo nhắc làm Bảng đánh giá hiệu quả của Khóa học',
            ],
            [
                'code' => 'action_plan_approve_offline',
                'name' => 'Thông báo duyệt Bảng đánh giá hiệu quả đào tạo nhân viên',
            ],
            [
                'code' => 'action_plan_complete_offline',
                'name' => 'Thông báo đã làm bảng đánh giá hiệu suất',
            ],
            [
                'code' => 'action_plan_manager_review_online',
                'name' => 'Thông báo đánh giá Bảng đánh giá hiệu quả đào tạo nhân viên',
            ],
            [
                'code' => 'grading_quiz',
                'name' => 'Thông báo giảng viên chấm điểm thi',
            ],
            [
                'code' => 'offline_approved_attendance',
                'name' => 'Thông báo điểm danh học viên',
            ],
            [
                'code' => 'approve_online',
                'name' => 'Duyệt khóa học online',
            ],
            [
                'code' => 'approve_offline',
                'name' => 'Duyệt khóa học offline',
            ],
            [
                'code' => 'approve_online_register',
                'name' => 'Duyệt ghi danh online',
            ],
            [
                'code' => 'approve_offline_register',
                'name' => 'Duyệt ghi danh offline'
            ],
            [
                'code' => 'approve_online_register_unit',
                'name' => 'Duyệt ghi danh online đơn vị',
            ],
            [
                'code' => 'approve_offline_register_unit',
                'name' => 'Duyệt ghi danh offline đơn vị'
            ],
            [
                'code' => 'register_approved_online',
                'name' => 'Ghi danh online đã được duyệt'
            ],
            [
                'code' => 'register_approved_offline',
                'name' => 'Ghi danh offline đã được duyệt'
            ],
            [
                'code' => 'approve_quiz',
                'name' => 'Duyệt kỳ thi',
            ],
            [
                'code' => 'course_online_change',
                'name' => 'Báo thay đổi khóa online',
            ],
            [
                'code' => 'course_offline_change',
                'name' => 'Báo thay đổi khóa offline',
            ],
            [
                'code' => 'course_quiz_change',
                'name' => 'Báo thay đổi khóa kỳ thi',
            ],
            [
                'code' => 'course_online_invitation',
                'name' => 'Thư mời tham gia khóa học online',
            ],
            [
                'code' => 'course_offline_invitation',
                'name' => 'Thư mời tham gia khóa học offline',
            ],
            [
                'code' => 'quiz_invitation',
                'name' => 'Thư mời tham dự kỳ thi',
            ],
            [
                'code' => 'register_quiz_remind',
                'name' => 'Thư nhắc tham dự kỳ thi'
            ],
            [
                'code' => 'probation_report_remind_01',
                'name' => 'Thư nhắc Thực hiện báo cáo thử việc trước 1 ngày',
            ],
            [
                'code' => 'probation_report_remind_15',
                'name' => 'Thư nhắc Thực hiện báo cáo thử việc trước 15 ngày',
            ],
            [
                'code' => 'review_action_plan_manager_online',
                'name' => 'Thư Đánh giá Đánh giá hiệu quả đào tạo của nhân viên online.',
            ],
            [
                'code' => 'review_action_plan_manager_offline',
                'name' => 'Thư Đánh giá Đánh giá hiệu quả đào tạo của nhân viên offline.',
            ],
            [
                'code' => 'action_plan_online',
                'name' => 'Thư thực hiện Đánh giá hiệu quả đào tạo của nhân viên online.',
            ],
            [
                'code' => 'action_plan_offline',
                'name' => 'Thư thực hiện Đánh giá hiệu quả đào tạo của nhân viên offline.',
            ],
            [
                'code' => 'review_action_plan_online',
                'name' => 'Thư thực hiện đánh giá Đánh giá hiệu quả đào tạo của nhân viên online.',
            ],
            [
                'code' => 'review_action_plan_offline',
                'name' => 'Thư thực hiện đánh giá Đánh giá hiệu quả đào tạo của nhân viên offline.',
            ],
            [
                'code' => 'approve_action_plan',
                'name' => 'Thư duyệt Đánh giá hiệu quả đào tạo.',
            ],
            [
                'code' => 'unit_approve_evaluate_employees',
                'name' => 'Mail thông báo cho Trưởng đơn vị vào duyệt báo cáo thử việc.',
            ],
            [
                'code' => 'approve_evaluate_employees',
                'name' => 'Mail thông báo cho Phòng nhân sự vào duyệt báo cáo thử việc.',
            ],
            [
                'code' => 'manager_approve_evaluate_employees',
                'name' => 'Mail thông báo cho TGĐ vào duyệt báo cáo thử việc.',
            ],
            [
                'code'=>'register_course_offline_object',
                'name'=>'Thông báo ghi danh khóa học offline theo đối tượng'
            ],
            [
                'code'=>'offline_completed',
                'name'=>'Thông báo hoàn thành khóa học offline'
            ],
            [
                'code'=>'declined_enroll_offline',
                'name'=>'Thông báo bị từ chối ghi danh khóa học offline'
            ],
            [
                'code'=>'delete_course_offline',
                'name'=>'Thông báo khóa học offline bị xóa'
            ],
            [
                'code'=>'action_plan_reminder_offline_01',
                'name'=>'Thông báo lập Bảng đánh giá hiệu quả của Khóa học offline'
            ],
            [
                'code'=>'action_plan_reminder_offline_02',
                'name'=>'Thông báo nhắc làm Bảng đánh giá hiệu quả của Khóa học offline'
            ],
            [
                'code'=>'action_plan_approve_online',
                'name'=>'Thông báo duyệt Bảng đánh giá hiệu quả đào tạo nhân viên'
            ],
            [
                'code'=>'action_plan_manager_review_offline',
                'name'=>'Thông báo đánh giá Bảng đánh giá hiệu quả đào tạo nhân viên'
            ],
            [
                'code'=>'action_plan_complete_online',
                'name'=>'Thông báo đã hoàn thành bảng đánh giá hiệu suất'
            ]
        ]);
    }
}
