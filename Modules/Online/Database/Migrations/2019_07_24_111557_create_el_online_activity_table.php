<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElOnlineActivityTable extends Migration
{
    public function up()
    {
        Schema::create('el_online_activity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 150)->unique();
            $table->string('name');
            $table->text('description');
            $table->string('icon');
            $table->timestamps();
        });

        DB::table('el_online_activity')->insert([
            [
                'code' => 'scorm',
                'name' => 'Gói SCORM',
                'description' => '<p>Gói SCORM là tập hợp các tệp được đóng gói theo tiêu chuẩn đã được đồng ý cho các đối tượng học tập. Mô-đun hoạt động SCORM cho phép các gói SCORM hoặc AICC được tải lên dưới dạng tệp zip và được thêm vào một khóa học.</p>

<p>Nội dung thường được hiển thị trên nhiều trang, với điều hướng giữa các trang. Có nhiều lựa chọn để hiển thị nội dung trong một cửa sổ bật lên, với một bảng mục lục, với các nút điều hướng, vv Các hoạt động SCORM thường bao gồm các câu hỏi, với các điểm được ghi trong sổ điểm.</p>

<p>Các hoạt động SCORM có thể được sử dụng</p>

<ul><li>Để trình bày nội dung đa phương tiện và hình ảnh động</li>
<li>Là một công cụ đánh giá</li>
</ul>',
                'icon' => '/styles/module/online/images/scrom.svg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'quiz',
                'name' => 'Kỳ thi',
                'description' => '<p>Hoạt động đố vui cho phép giáo viên tạo ra các câu đố bao gồm các câu hỏi thuộc nhiều loại khác nhau, bao gồm nhiều lựa chọn, kết hợp, trả lời ngắn và số.</p>
<p>Giáo viên có thể cho phép làm bài kiểm tra nhiều lần, với các câu hỏi được xáo trộn hoặc được chọn ngẫu nhiên từ ngân hàng câu hỏi. Một giới hạn thời gian có thể được thiết lập.</p>
<p>Mỗi lần thử được đánh dấu tự động, ngoại trừ các câu hỏi tiểu luận và điểm được ghi vào sổ điểm.</p>
<p>Giáo viên có thể chọn thời điểm và nếu gợi ý, phản hồi và câu trả lời đúng được hiển thị cho học sinh.</p>
<p>Kỳ thi có thể được sử dụng</p>
<ul>
<li>Như bài kiểm tra khóa học</li>
<li>Là bài kiểm tra nhỏ để đọc bài tập hoặc ở cuối chủ đề</li>
<li>Như bài thi thực hành sử dụng câu hỏi từ các kỳ thi trước</li>
<li>Để tự đánh giá</li>
</ul>',
                'icon' => '/styles/module/online/images/quiz.svg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'file',
                'name' => 'Tệp tin / Tài liệu',
                'description' => '<p>Mô-đun tệp cho phép giáo viên cung cấp tệp dưới dạng tài nguyên khóa học. Nếu có thể, tập tin sẽ được hiển thị trong giao diện khóa học; nếu không sinh viên sẽ được nhắc tải xuống. Tệp có thể bao gồm các tệp hỗ trợ, ví dụ: trang HTML có thể có hình ảnh nhúng hoặc đối tượng Flash.</p>

<p>Lưu ý rằng sinh viên cần phải có phần mềm thích hợp trên máy tính để mở tệp.</p>
<p>Một tập tin có thể được sử dụng</p>
<ul>
<li>Để chia sẻ bài thuyết trình được đưa ra trong lớp</li>
<li>Để bao gồm một trang web nhỏ như một tài nguyên khóa học</li>
<li>Để cung cấp các tệp nháp của một số chương trình phần mềm nhất định (ví dụ: Photoshop ..) Để sinh viên có thể chỉnh sửa và gửi chúng để đánh giá</li>
</ul>',
                'icon' => '/styles/module/online/images/file.svg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'url',
                'name' => 'URL',
                'description' => '<p>Mô-đun URL cho phép giáo viên cung cấp liên kết web dưới dạng tài nguyên khóa học. Bất cứ điều gì có sẵn miễn phí trực tuyến, như tài liệu hoặc hình ảnh, có thể được liên kết đến; URL không phải là trang chủ của một trang web. URL của một trang web cụ thể có thể được sao chép và dán hoặc giáo viên có thể sử dụng trình chọn tệp và chọn liên kết từ kho lưu trữ như Flickr, YouTube hoặc Wikimedia (tùy thuộc vào kho lưu trữ nào được bật cho trang web).</p>

<p>Có một số tùy chọn hiển thị cho URL, chẳng hạn như được nhúng hoặc mở trong một cửa sổ mới và các tùy chọn nâng cao để truyền thông tin, như tên của học sinh, đến URL nếu được yêu cầu.</p>
<p>Lưu ý rằng URL cũng có thể được thêm vào bất kỳ loại tài nguyên hoặc hoạt động nào khác thông qua trình soạn thảo văn bản.</p>',
                'icon' => '/styles/module/online/images/url.svg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'video',
                'name' => 'Video',
                'description' => '<p>Tài liệu đào tạo video, định dạng mp4.</p>',
                'icon' => '/styles/module/online/images/play.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'code' => 'virtualclassroom',
                'name' => 'Khóa học online',
                'description' => '<p>Khóa học online.</p>',
                'icon' => '/styles/module/online/images/touch.png',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('el_online_activity');
    }
}
