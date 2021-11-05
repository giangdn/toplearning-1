<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElNotifyTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('el_notify_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name', 255);
            $table->string('title', 255);
            $table->text('content');
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by')->default(1)->nullable();
            $table->integer('updated_by')->default(1)->nullable();
            $table->integer('unit_by')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('el_notify_template');
    }

    public function insertData() {
        \DB::table('el_notify_template')->insert([
            [
                'code' => 'registered_course',
                'name' => 'Thông báo đã được duyệt ghi danh khóa học',
                'title' => '{gender} {full_name} vừa được ghi danh vào khóa học {course_name} (Mã khóa học:{course_code})',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>{gender} {full_name} vừa được ghi danh khóa học {course_name} (Mã khóa học:{course_code}).</p>
                    <p>- Thời gian: {start_date} - {end_date}.</p>
                    <p>- Ngày bắt đầu học là {start_date}</p>
                    <p>- Địa điểm: {training_location}</p>
                    <p>- Hình thức: {course_type}</p>
                    <p>Nào, hãy sắp xếp lại kế hoạch và công việc của {gender} {full_name} để tham gia đầy đủ và hoàn thành khóa học {gender} {full_name} nhé.</p>
                    <p>Vui lòng bấm vào {url} để xem chi tiết khóa học.</p>
                    <p>Chúc {gender} {full_name} hoàn thành khóa học với kết quả cao nhất!</p>
                    <p>Trân trọng.</p>',
                'note' => 'Kiểm lúc ghi danh (import/Ghi danh thủ công + đã duyệt)'
            ],
            [
                'code' => 'register_course_object',
                'name' => 'Thông báo ghi danh khóa học thuộc đối tượng',
                'title' => '{gender} {full_name} thuộc đối tượng tham gia, đăng ký khóa học {course_name} (Mã khóa học:{course_code})',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>{gender} {full_name} thuộc đối tượng tham gia, đăng ký khóa học {course_name} (Mã khóa học:{course_code}).</p>
                    <p>- Thời gian: {start_date} - {end_date}.</p>
                    <p>- Ngày bắt đầu học là {start_date}</p>
                    <p>- Địa điểm: {training_location}</p>
                    <p>- Hình thức: {course_type}</p>
                    <p>Nào, hãy sắp xếp lại kế hoạch và công việc của {gender} {full_name} để đăng ký khóa học.</p>
                    <p>Vui lòng bấm vào {url} để xem chi tiết khóa học.</p>
                    <p>Chúc {gender} {full_name} hoàn thành khóa học với kết quả cao nhất!</p>
                    <p>Trân trọng.</p>',
                'note' => 'Khi khóa học được duyệt và bật'
            ],
            [
                'code' => 'online_reminder_01',
                'name' => 'Thông báo khóa học sắp kết thúc',
                'title' => 'Sắp hết hạn khóa học offline {courseName} (Mã khóa học:{courseCode})',
                'content' => '<p>Chào {Gender} {FirstName}</p>
                    <p>Còn {Duration} ngày nữa, khóa học {courseName} (Mã khóa học:{courseCode}) sẽ kết thúc.</p>
                    <p>- Hình thức: {courseType}</p>
                    <p>- Thời gian: {startDate} - {endDate}.</p>
                    <p>- {Gender} {FirstName} đã hoàn thành là {Progress}%</p>
                    <p>Hãy sắp xếp lại kế hoạch và công việc của {Gender} {FirstName} để tham gia khóa học theo đúng Quy định.</p>
                    <p>Vui lòng bấm vào {url} để xem chi tiết khóa học.</p>
                    <p>Chúc {Gender} {FirstName} hoàn thành khóa học với kết quả cao nhất!</p>
                    <p>Trân trọng.</p>',
                'note' => 'Khóa học offline với các hoạt động chưa đủ 100%. Học viên nằm trong danh sách đã duyệt'
            ],
            [
                'code' => 'online_reminder_02',
                'name' => 'Thông báo đã lâu không tham gia khóa học',
                'title' => 'Đã lâu không thấy bạn tham gia khóa học offline {courseName} (Mã khóa học:{courseCode})',
                'content' => '<p>Chào {Gender} {FirstName}</p>
                    <p>Chúng tôi nhận thấy {Gender} {FirstName}rất lâu không có tham gia khóa học {courseName} (Mã khóa học:{courseCode})</p>
                    <p>- Hình thức: {courseType}</p>
                    <p>- Ngày bắt đầu:  {startDate}</p>
                    <p>- {Gender} {FirstName} đã hoàn thành là {Progress}%</p>
                    <p>Hãy sắp xếp lại kế hoạch và công việc của {Gender} {FirstName} để tham gia khóa học theo đúng Quy định.</p>
                    <p>Vui lòng bấm vào {url} để xem chi tiết khóa học.</p>
                    <p>Chúc {Gender} {FirstName} luôn thành công!</p>
                    <p>Trân trọng.</p>',
                'note' => 'Khóa học offline với các hoạt động chưa đủ 100%. Học viên nằm trong danh sách đã duyệt. Ngày tham gia hoạt động cuối cùng so với ngày hiện tại là [[A]]'
            ],
            [
                'code' => 'course_completed',
                'name' => 'Thông báo hoàn thành khóa học',
                'title' => 'Thông báo hoàn thành khóa học {course_name} (Mã khóa học:{course_code})',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>Xin chúc mừng {gender} {full_name} đã tham gia và hoàn thành các hoạt động/nội dung trong khóa học {course_name} (Mã khóa học:{course_code})</p>
                    <p>- Hình thức: {course_type}</p>
                    <p>- Ngày bắt đầu: {start_date}</p>
                    <p>- {gender} {full_name} đã hoàn thành là {progress}%</p>
                    <p>- Kết quả: {completion}</p>
                    <p>Lưu ý: Nếu khóa học của {gender} {full_name} có bắt buộc thực hiện bảng khảo sát hoặc Bảng đánh giá hiệu suất đào tạo thì {gender} {full_name} hãy thực hiện nhé.</p> 
                    <p>Chúc {gender} {full_name} có thể ứng dụng những kiến thức đã học một cách hiệu quả!</p>
                    <p>Trân trọng.</p>',
                'note' => 'Khóa học offline với các hoạt động hoàn thành 100%. Học viên nằm trong danh sách đã duyệt'
            ],
            [
                'code' => 'quiz_registerd',
                'name' => 'Thông báo đã được ghi danh kỳ thi',
                'title' => '{gender} {full_name} đã được ghi danh vào kỳ thi {quiz_name}',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>{gender} {full_name} được ghi danh vào kỳ thi {quiz_name}</p>
                    <p>Loại bài thi: {quiz_type} (Bài thi của khóa học In House/Kỳ thi kiểm tra độc lập)</p>
                       <p>+ Ca thi: {quiz_part_name}</p>
                       <p>+ Thời gian bắt đầu ca thi: {start_quiz_part} - {end_quiz_part}</p>
                       <p>+ Thời gian thi: {quiz_time}</p> 
                       <p>+ Điểm đạt: {pass_score}</p>
                    <p>Vui lòng bấm vào {url} để xem chi tiết.</p>
                    <p>Chúc {gender} {full_name} hoàn thành kỳ thi với kết quả cao nhất!</p>
                    <p>Trân trọng.</p>',
                'note' => 'Ghi danh vào trong kỳ thi. Gửi trước [[B]] ngày so với ngày bắt đầu kỳ thi. Áp dụng: Kỳ thi độc lập, kỳ thi của khóa học tập trung'
            ],
            [
                'code' => 'quiz_result',
                'name' => 'Thông báo kết quả kỳ thi',
                'title' => 'Thông báo kết quả kỳ thi {quiz_name} ca thi {quiz_part_name} tổ chức ngày {start_quiz_part} - {end_quiz_part}',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>{gender} {full_name} đã hoàn thành bài thi {quiz_name}</p>
                    <p>Thời gian ca thi: {start_quiz_part} - {end_quiz_part}.</p>
                    <p>Thời gian thi: {quiz_time}</p>
                    <p>Loại bài thi: {quiz_type} (Bài kiểm tra trong khóa học offline/Bài thi của khóa học In House/Kỳ thi kiểm tra độc lập)</p>
                    <p>Kết quả thi: {quiz_result}</p>
                    <p>Vui lòng bấm vào {url} để xem chi tiết.</p>
                    <p>Chúc {gender} {full_name} luôn thành công!</p>
                    <p>Trân trọng.</p>',
                'note' => 'Ngay khi học viên hoàn thành bài thi'
            ],
            [
                'code' => 'course_cance',
                'name' => 'Thông báo khóa học bị hủy',
                'title' => 'Thông báo Khóa học {courseName} ({courseCode}) của {Gender} {FirstName} đang đăng ký đã bị hủy',
                'content' => '<p>Chào {Gender} {FirstName}</p>
                    <p>Khóa học {courseName} (Mã khóa học:{courseCode}) đã bị Hủy. Lý do: {reason}</p>
                    <p>- Thời gian: {startDate} - {endDate}.</p>
                    <p>- Ngày bắt đầu học là {startDate}</p>
                    <p>- Địa điểm: {trainingLocation}</p>
                    <p>- Hình thức: {courseType}</p>
                    <p>Vui lòng liên hệ giáo viên để biết thêm chi tiết.</p>
                    <p>Chúc {Gender} {FirstName} luôn thành công!</p>
                    <p>Trân trọng.</p>',
                'note' => 'Khóa học bấm nút Hủy và bổ sung lý do'
            ],
            [
                'code' => 'declined_enroll',
                'name' => 'Thông báo bị từ chối ghi danh',
                'title' => '{Gender} {FirstName} chưa phù hợp khóa học {courseName} (Mã khóa học:{courseCode})',
                'content' => '<p>Chào {Gender} {FirstName}</p>
                    <p>{Gender} {FirstName} chưa phù hợp khóa học {courseName} (Mã khóa học:{courseCode}) của lần tổ chức này.</p> 
                    <p>Vui lòng liên hệ người phụ trách tổ chức đào tạo để biết thêm thông tin.</p>
                    <p>Trân trọng thông báo.</p>',
                'note' => 'Học viên bị từ chối ghi danh hoặc Đã duyệt nhưng sau đó từ chối và bị xoá'
            ],
            [
                'code' => 'delete_course',
                'name' => 'Thông báo khóa học bị xóa',
                'title' => 'Khóa học {course_name} của {gender} {full_name} đã bị xóa',
                'content' => '<p>Kính gửi thông báo đến {gender} {full_name}</p>
                    <p>Khóa học {course_name} mã khóa học: {course_code} của {gender} {full_name} đã bị xóa do thời gian bảo lưu đã hết hạn.</p>
                    <p>Trân trọng.</p>',
                'note' => 'Học viên đã ghi danh, khóa học chưa tổ chức, xóa'
            ],
            [
                'code' => 'action_plan_reminder_01',
                'name' => 'Thông báo Đánh giá hiệu quả đào tạo',
                'title' => 'Thông báo Đánh giá hiệu quả đào tạo',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>{gender} thuộc đối tượng làm đánh giá {rating_name}.</p> 
                    <p>{gender} {full_name} sắp xếp thời gian để thực hiện bài Đánh giá hiệu quả đào tạo trong thời gian sớm nhất.</p>
                    <p>Hãy bấm vào {url} để làm bài đánh giá hiệu quả của khóa học.</p>
                    <p>Trân trọng thông báo.</p>',
                'note' => 'Gửi khi thêm đối tượng đánh giá đào tạo'
            ],
            [
                'code' => 'action_plan_reminder_02',
                'name' => 'Thông báo nhắc làm Đánh giá hiệu quả đào tạo',
                'title' => 'Thông báo nhắc làm Đánh giá hiệu quả đào tạo',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>Hiện tại, Bộ phận đào tạo chưa thấy {gender} {full_name} thực hiện Đánh giá hiệu quả đào tạo {rating_name}.</p>
                    <p>Đề nghị {gender} {full_name} sắp xếp thời gian để thực hiện đánh giá hiệu quả đào tạo trong thời gian sớm nhất.</p>
                    <p>Hãy bấm vào {url} để làm bản đánh giá hiệu quả khóa học.</p>
                    <p>Trân trọng thông báo.</p>',
                'note' => 'Trước kết thúc đánh giá 1 ngày. Chỉ gửi những đối tượng nào chưa làm.'
            ],
            [
                'code' => 'action_plan_approve',
                'name' => 'Thông báo duyệt Bảng đánh giá hiệu quả đào tạo nhân viên',
                'title' => 'Thực hiện duyệt Bảng đánh giá hiệu quả đào tạo của khóa học {course_name} với ngày bắt đầu là {end_date}',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>Khóa học {course_name} (Mã khóa học:{course_code}) với thời gian diễn ra {start_date} - {end_date} vừa hoàn thành.</p> 
                    <p>{employee_name} {employee_code} đã thực hiện lập Bảng đánh giá hiệu quả của khóa học {action_plan}.</p>
                    <p>Hãy bấm vào {url} để duyệt đánh giá hiệu quả đào tạo khóa học.</p>
                    <p>Trân trọng thông báo.</p>',
                'note' => 'Trưởng đơn vị trực tiếp tại đơn vị, sau khi học viên lập bảng đánh giá hiệu quả đào tạo'
            ],
            [
                'code' => 'action_plan_complete',
                'name' => 'Thông báo đã làm bảng đánh giá hiệu suất',
                'title' => '{gender} {full_name} đã hoàn thành Bảng đánh giá hiệu suất của khóa học {course_name}',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>Xin chúc mừng, {gender} {full_name} đã hoàn thành Bảng đánh giá hiệu quả đào tạo của khóa học {course_name} {course_code} vào ngày {date_action_plan}</p>
                    <p>Trân trọng thông báo.</p>',
                'note' => 'Sau khi học viên làm và nộp bảng đánh giá'
            ],
            [
                'code' => 'action_plan_manager_review',
                'name' => 'Thông báo đánh giá Bảng đánh giá hiệu quả đào tạo nhân viên',
                'title' => 'Thực hiện đánh giá Bảng đánh giá hiệu quả đào tạo của khóa học {courseName} với ngày bắt đầu là {endDate}',
                'content' => '<p>Chào {Gender} {FirstName}</p>
                    <p>{EmployeeName} {EmployeeCode} đã thực hiện lập Bảng đánh giá hiệu quả của khóa học {ActionPlan} cho Khóa học {courseName} (Mã khóa học:{courseCode}) với thời gian diễn ra {startDate} - {endDate}.</p>
                    <p>Hãy bấm vào {url} để đánh giá hiệu quả đào tạo khóa học.</p>
                    <p>Trân trọng thông báo.</p>',
                'note' => 'Trưởng đơn vị trực tiếp tại đơn vị, sau khi học viên thực hiện bảng đánh giá hiệu quả đào tạo'
            ],
            [
                'code' => 'grading_quiz',
                'name' => 'Thông báo giảng viên chấm điểm thi',
                'title' => 'Thông báo chấm điểm tự luận của kỳ thi {quiz_name}',
                'content' => '<p>Kính gửi: Quý thầy cô ciảng viên {name}</p>
                    <p>Kỳ thi {quiz_name} vừa kết thúc. Bộ phận đào tạo kính mong Quý thầy cô giảng viên {name} sắp xếp thời gian để tiến hành chấm điểm của phần tự luận của ca thi {quiz_part_name}.</p> 
                    <p>Chi tiết kỳ thi như sau:</p>
                    <p>- Tên kỳ thi: {quiz_name} (Mã kỳ thi: quiz_code}</p>
                    <p>- Ca thi: {quiz_part_name}</p>
                    <p>- Thời gian của ca thi: {start_quiz_part} - {end_quiz_part}.</p>
                    <p>- Thời gian bắt đầu chấm điểm tự luận: {end_quiz_part}</p> 
                    <p>Có thể truy cập vào {url} để vào bài thi tự luận.</p>
                    <p>Kính mong, Quý thầy cô giảng viên {name} sắp xếp thời gian để hoàn tất việc chấm điển để Bộ phận đào tạo tiến hành báo cáo kết quả học tập.</p>
                    <p>Trân trọng thông báo.</p>',
                'note' => 'Sau khi kết thúc kỳ thi sẽ tự động gửi và trước thời hạn chấm điểm kỳ thi 1 ngày'
            ],
            [
                'code' => 'offline_approved_attendance',
                'name' => 'Thông báo điểm danh học viên',
                'title' => 'Kiểm tra danh sách học viên của khóa {courseName} (Mã khóa học:{courseCode})',
                'content' => '<p>Kính gửi: {Gender} {FirstName} - Phụ trách bộ phận đào tạo</p>
                    <p>Khóa học {courseName} (Mã khóa học:{courseCode}) sắp bắt đầu.</p> 
                    <p>Bộ phận đào tạo đề nghị {Gender} {FirstName} vui lòng sắp xếp thời gian để kiểm tra lại danh sách học viên trước khi khóa học bắt đầu diễn ra trong thời gian tới.</p> 
                    <p>- Thời gian tổ chức:  {startDate} - {endDate}</p>
                    <p>Hiện tại, khóa học của có {HocVienDuyet} tham gia so với {TongSoHocVienDangKy}.</p> 
                    <p>Trân trọng thông báo.</p>',
                'note' => 'Thông báo trước 1 ngày so với ngày bắt đầu khóa học'
            ],
            [
                'code' => 'approve_quiz',
                'name' => 'Thông báo duyệt kỳ thi',
                'title' => 'Duyệt tổ chức kỳ thi',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>Kỳ thi {quiz_name} cần được duyệt để tổ chức thi cho học viên.</p>
                    <p>Loại bài thi: {quiz_type}</p>
                    <p>Thời gian thi: {quiz_time}</p>
                    <p>Vui lòng bấm vào {url} để xem chi tiết.</p>
                    <p>Trân trọng.</p>',
                'note' => 'Đối tượng nhận: Nhân sự được phân quyền duyệt<br> Thời gian gửi: Sau khi nhân sự được phân quyền tạo kỳ thi bấm gửi Duyệt.'
            ],
            [
                'code' => 'register_quiz_remind',
                'name' => 'Thư nhắc tham dự kỳ thi',
                'title' => 'Thư nhắc tham dự kỳ thi {name}',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Kỳ thi <b>{name}</b> được tổ chức từ {start_date} đến {end_date}</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>
                        ',
                'note' => 'Đối tượng nhận: Học viên, Trưởng đơn vị<br> Thời gian gửi: trước ngày bắt đầu kỳ thi 1 ngày.'
            ],
            /*[
                'code' => 'approve_register_unit',
                'name' => 'Thư báo Duyệt đăng ký tham gia khóa học',
                'title' => 'Đăng ký tham gia khóa học {name} cần được duyệt',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Ghi danh của khóa học <b>{name}</b> cần được duyệt</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem và duyệt đăng ký khóa học.</p>
                        ',
                'note' => 'Đối tượng nhận: Trưởng đơn vị. <br>Thời gian gửi: ngay sau khi Học viên đăng ký tham gia khóa học.'
            ],
            [
                'code' => 'approve_register',
                'name' => 'Thư báo Duyệt đăng ký tham gia khóa học',
                'title' => 'Đăng ký tham gia khóa học {name} cần được duyệt',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Ghi danh của khóa học {name} cần được duyệt</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem và duyệt đăng ký khóa học.</p>
                        ',
                'note' => 'Đối tượng nhận: Trung tâm đào tạo. <br>Thời gian gửi: ngay sau khi Trưởng đơn vị duyệt nhân viên đơn vị đăng ký tham gia.'
            ],
            [
                'code' => 'register_approved',
                'name' => 'Đăng ký khóa học đã được duyệt',
                'title' => 'Đăng ký tham gia khóa học {name} đã được duyệt',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Đăng ký tham gia khóa học {name} đã được duyệt</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>
                        ',
                'note' => 'Đối tượng nhận: Học viên, Trưởng đơn vị. <br>Thời gian gửi: ngay sau khi Học viên được duyệt tham gia khóa học.'
            ],
            [
                'code' => 'register_invitation',
                'name' => 'Thư mời tham gia khóa học',
                'title' => 'Thư mời tham gia khóa học {name}',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Khóa học <b>{name}</b> sắp được tổ chức</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>
                        ',
                'note' => 'Đối tượng nhận: Học viên, Trưởng đơn vị. <br>Thời gian gửi: trước ngày bắt đầu khóa học 2 ngày.'
            ],
            [
                'code' => 'register_quiz_invitation',
                'name' => 'Thư mời tham dự kỳ thi',
                'title' => 'Thư mời tham dự kỳ thi {name}',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Kỳ thi <b>{name}</b> được tổ chức từ {start_date} đến {end_date}</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>
                        ',
                'note' => 'Đối tượng nhận: Học viên, Trưởng đơn vị <br> Thời gian gửi: nhân sự phụ trách kỳ thi bấm nút gửi mail.'
            ],
            [
                'code' => 'action_plan',
                'name' => 'Thư Thực hiện Đánh giá hiệu quả đào tạo.',
                'title' => 'Khóa học {name} có Đánh giá hiệu quả đào tạo cần hoàn thành',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Khóa học <b>{name}</b> có Đánh giá hiệu quả đào tạo cần phải hoàn thành</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>
                        ',
                'note' => 'Đối tượng nhận: Học viên<br> Thời gian gửi: sau khi kết thúc khóa học 1 ngày.'
            ],
            [
                'code' => 'action_plan_remind',
                'name' => 'Thư nhắc Thực hiện Đánh giá hiệu quả đào tạo.',
                'title' => 'Khóa học {name} có Đánh giá hiệu quả đào tạo cần hoàn thành',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Khóa học <b>{name}</b> có Đánh giá hiệu quả đào tạo cần phải hoàn thành</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>
                        ',
                'note' => 'Đối tượng nhận: Học viên<br> Thời gian gửi: sau khi kết thúc khóa học 8 ngày.'
            ],
            [
                'code' => 'probation_report_remind_15',
                'name' => 'Thư Thực hiện báo cáo thử việc trước 15 ngày.',
                'title' => 'Thư nhắc thực hiện báo cáo thử việc.',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Anh/ Chị cần hoàn thành báo cáo thử việc trong 15 ngay tới</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>
                        ',
                'note' => ' Đối tượng nhận: Học viên<br> Thời gian gửi: trước kết thúc thử việc 15 ngày.'
            ],
            [
                'code' => 'probation_report_remind_01',
                'name' => 'Thư nhắc Thực hiện báo cáo thử việc trước 1 ngày.',
                'title' => 'Còn 1 ngày để thực hiện báo cáo thử việc',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Anh/ Chị chỉ còn 1 ngày để hoàn thành báo cáo thử việc</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>
                        ',
                'note' => 'Đối tượng nhận: Học viên<br>Thời gian gửi: trước kết thúc thử việc 01 ngày'
            ],
            [
                'code' => 'approve_action_plan',
                'name' => 'Thư duyệt Đánh giá hiệu quả đào tạo.',
                'title' => 'Có Đánh giá hiệu quả đào tạo cần duyệt',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Đánh giá hiệu quả đào tạo của nhân viên thuộc đơn vị cần được duyệt</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>',
                'note' => 'Đối tượng nhận: Trưởng đơn vị<br> Thời gian gửi: sau khi nhân viên thuộc đơn vị gửi Đánh giá hiệu quả đào tạo.'
            ],
            [
                'code' => 'review_action_plan',
                'name' => 'Thư Thực hiện đánh giá Đánh giá hiệu quả đào tạo.',
                'title' => 'Thông báo đánh giá hiệu quả đào tạo của khóa học {course_name}',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>Khóa học {course_name} (Mã khóa học:{course_code}) với thời gian diễn ra {start_date} - {end_date}.</p>
                    <p>{gender} {full_name} sắp xếp thời gian để thực hiện đánh giá hiệu quả của khóa học {action_plan} mà bạn đã lập kế hoạch trước đây.</p>
                    <p>Hãy bấm vào {url} để làm bản đánh giá hiệu quả khóa học.</p>
                    <p>Trân trọng thông báo.</p>',
                'note' => 'Đối tượng nhận: Học viên<br> Thời gian gửi: trước 2 ngày khi tới hạn tự đánh giá Đánh giá hiệu quả đào tạo.'
            ],
            [
                'code' => 'review_action_plan_manager',
                'name' => 'Thư duyệt đánh giá hiệu quả đào tạo của nhân viên.',
                'title' => 'Thực hiện duyệt đánh giá hiệu quả đào tạo của học viên khóa học {course_name}',
                'content' => '<p>Chào {gender} {full_name}</p>
                    <p>{employee_name} {employee_code} đã thực hiện lập Bảng đánh giá hiệu quả khóa học: {action_plan} cho Khóa học {course_name} (Mã khóa học:{course_code}) </p>
                    <p>Thời gian diễn ra {start_date} - {end_date}.</p>
                    <p>Hãy bấm vào {url} để duyệt đánh giá của học viên.</p>
                    <p>Trân trọng thông báo.</p>',
                'note' => 'Đối tượng nhận: Trưởng đơn vị<br> Thời gian gửi: Sau khi nhân viên thuộc đơn vị gửi Tự đánh giá Đánh giá hiệu quả đào tạo.'
            ],
            [
                'code' => 'approve_course',
                'name' => 'Thư Duyệt tổ chức khóa học',
                'title' => 'Khóa học mới cần được duyệt tổ chức',
                'content' => '',
                'note' => 'Đối tượng nhận: Nhân sự được phân quyền duyệt<br> Thời gian gửi: Sau khi nhân sự được phân quyền tạo khóa bấm gửi Duyệt.'
            ],
            [
                'code' => 'course_change',
                'name' => 'Mail thông báo thay đổi thông tin khóa học.',
                'title' => 'Thông báo thông tin khóa học {name} đã thay đổi',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Khóa học <b>{name}</b> đã thay đổi thông tin</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>
                        ',
                'note' => 'Đối tượng nhận: Học viên, Trưởng đơn vị<br> Thời gian gửi: Sau khi nhân sự được phân quyền bấm nút gửi mail.'
            ],
            [
                'code' => 'quiz_change',
                'name' => 'Mail thông báo thay đổi thông tin kỳ thi.',
                'title' => 'Thông báo thông tin kỳ thi {name} đã thay đổi',
                'content' => '<p>Kính gửi Anh/ Chị,</p>
                        <p>Kỳ thi <b>{name}</b> đã thay đổi thông tin</p>
                        <p>Anh/Chị vui lòng truy cập {url} để xem chi tiết.</p>
                        ',
                'note' => 'Đối tượng nhận: Học viên, Trưởng đơn vị<br> Thời gian gửi: Sau khi nhân sự được phân quyền bấm nút gửi mail.'
            ],
            [
                'code' => 'unit_approve_evaluate_employees',
                'name' => 'Mail thông báo cho Trưởng đơn vị vào duyệt báo cáo thử việc.',
                'title' => 'Thông báo cho Trưởng đơn vị vào duyệt báo cáo thử việc',
                'content' => '<p>Kính gửi quý Anh/ Chị,</p>
                        <p>Nhân viên <b>{code} - {name}</b> thử việc từ {start_date} đến {end_date} đã hoàn thành báo cáo thử việc trên hệ thống Elearning.</p>
                        <p>Kính nhờ quý Anh/Chị vui lòng truy cập {url} để đánh giá, phê duyệt báo cáo của nhân sự tân tuyển trên.</p>
                        <p>Trân trọng thông tin đến quý Anh/chị.</p>
                        ',
                'note' => 'Đối tượng nhận: Trưởng đơn vị<br> Thời gian gửi: Sau khi nhân sự bấm nút gửi mail.'
            ],
            [
                'code' => 'approve_evaluate_employees',
                'name' => 'Mail thông báo cho Phòng nhân sự vào duyệt báo cáo thử việc.',
                'title' => 'Thông báo cho Phòng nhân sự vào duyệt báo cáo thử việc',
                'content' => '<p>Kính gửi quý Anh/ Chị,</p>
                        <p>Nhân viên <b>{code} - {name}</b> thử việc từ {start_date} đến {end_date} đã hoàn thành báo cáo thử việc trên hệ thống Elearning.</p>
                        <p>Kính nhờ quý Anh/Chị vui lòng truy cập {url} để đánh giá, phê duyệt báo cáo của nhân sự tân tuyển trên.</p>
                        <p>Trân trọng thông tin đến quý Anh/chị.</p>
                        ',
                'note' => 'Đối tượng nhận: Phòng nhân sự<br> Thời gian gửi: Sau khi Trưởng đơn vị bấm nút gửi mail.'
            ],
            [
                'code' => 'manager_approve_evaluate_employees',
                'name' => 'Mail thông báo cho TGĐ vào duyệt báo cáo thử việc.',
                'title' => 'Thông báo cho TGĐ vào duyệt báo cáo thử việc',
                'content' => '<p>Kính gửi quý Anh/ Chị,</p>
                        <p>Nhân viên <b>{code} - {name}</b> thử việc từ {start_date} đến {end_date} đã hoàn thành báo cáo thử việc trên hệ thống Elearning.</p>
                        <p>Kính nhờ quý Anh/Chị vui lòng truy cập {url} để đánh giá, phê duyệt báo cáo của nhân sự tân tuyển trên.</p>
                        <p>Trân trọng thông tin đến quý Anh/chị.</p>
                        ',
                'note' => 'Đối tượng nhận: Tổng giám đốc<br> Thời gian gửi: Sau khi P.NS duyệt báo cáo nhân sự tân tuyển.'
            ],*/
        ]);
    }
}
