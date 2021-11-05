@extends('quiz::layout.app')

@section('page_title', $quiz->name)

@section('content')
    <div id="page-navbar" class="clearfix">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a itemprop="url" href="/"><span itemprop="title">Trang chủ</span></a></li>
                <li class="breadcrumb-item"><a itemprop="url" href="{{ route('module.quiz') }}"> {{ trans('backend.exam') }} </a></li>
                <li class="breadcrumb-item"><span tabindex="0">{{ $quiz->name }}</span></li>
            </ol>
        </nav>
        <div class="breadcrumb-button"></div>
    </div>

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card" id="card_quiz">
                <div class="card-body text-center">
                    <h3 class="card-title">CẤU TRÚC BÀI THI VÀ HƯỚNG DẪN LÀM BÀI</h3>
                    <div class="text-center">
                        <p>
                            Bài thi có tổng cộng {{ $count_quiz_question }} câu hỏi. <br>
                            {{ trans('app.time_exam') .': '. $quiz->limit_time .' '. trans('app.min') }} <br>
                            Số điểm cần đạt: {{ $quiz->pass_score }} <br>
                            Nội dung thi: {{ $quiz->description }} <br>
                            Thời gian bắt đầu tính khi bạn mở đề thi. <br>
                            Khi thời gian kết thúc bài thi sẽ nộp tự động. <br>
                            Vui lòng đọc kỹ đề bài trước khi trả lời. Chúc bạn thành công!
                        </p>
                    </div>
                    @if($can_create)
                        <form action="{{ route('module.quiz.doquiz.create_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post" class="form-ajax">
                            <button type="submit" class="btn btn-primary mt-2" {{--id="go-quiz"--}}><i class="fa fa-edit"></i> {{ data_locale('Vào làm bài thi', 'Into the test') }} </button>
                        </form>
                    @else
                        <p><b>{{ data_locale('Bạn đã hết số lần làm bài cho kỳ thi này', 'You have run out of exams for this exam') }}</b></p>
                    @endif
                </div>
            </div>
            {{--<p></p>
            <div id="history_quiz">
                <h4>Tóm tắt lịch sử</h4>
                <table class="tDefault table table-hover bootstrap-table table-bordered" >
                    <thead>
                        <tr>
                            <th data-formatter="index_formatter" data-align="center">#</th>
                            <th data-field="start_date">{{ trans('app.start_date') }}</th>
                            <th data-field="end_date">{{ trans('app.end_date') }}</th>
                            <th data-field="grade" data-align="center">{{ trans('app.score') }}</th>
                            <th data-field="status" data-align="center">{{ trans('app.status') }}</th>
                            <th data-field="review" data-align="center" data-formatter="review_formatter">{{ trans('app.review') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>--}}
        </div>
    </div>

    @if($can_create)
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('module.quiz.doquiz.create_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post" class="form-ajax">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ data_locale('Bắt đầu bài thi', 'Start the exam') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        {{ data_locale('Bài kiểm tra có giới hạn thời gian là', 'The test has a time limit of') }} <b>{{ $quiz->limit_time .' '. trans('app.min') .'.' }}</b><br>
                        {{ data_locale('Thời gian sẽ được tính từ thời điểm bạn bắt đầu bài làm của mình và bạn phải gửi trước khi hết hạn.', 'Time will be counted from the time you start your assignment and you must submit before it expires.') }} <br>
                        {{ data_locale('Thời gian vẫn sẽ tính kể cả khi bạn thoát hoặc đóng trình duyệt.', 'Time will still count even when you exit or close the browser.') }} <br>
                        {{ data_locale('Bạn có chắc chắn muốn bắt đầu ngay bây giờ không?', 'Are you sure you want to get started now?') }}
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> {{ data_locale('Làm bài thi', 'Take the test') }}</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> {{ trans('app.cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script type="text/javascript">
        $("#go-quiz").on('click', function () {
            $("#myModal").modal();
        });

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function review_formatter(value, row, index) {
            if (row.after_review == 1 || row.closed_review == 1) {
                return '<a href="'+ row.review_link +'">Xem lại</a>'
            }

            return '<span class="text-muted">Không được xem</span>';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz.doquiz.attempt_history', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}',
        });
    </script>

    {{--@if($quiz->webcam_require == 1)
        <script language="JavaScript">
            window.addEventListener("DOMContentLoaded", function() {
                // Grab elements, create settings, etc.
                var canvas = document.getElementById('canvas');
                //var context = canvas.getContext('2d');
                var video = document.getElementById('video');
                var mediaConfig =  { video: true };
                var errBack = function(e) {
                    console.log('An error has occurred!', e)
                };

                // Put video listeners into place
                if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia(mediaConfig).then(function(stream) {
                        //video.src = window.URL.createObjectURL(stream);
                        console.log(stream);
                        video.srcObject = stream;
                        video.play();
                    });
                }

                /* Legacy code below! */
                else if(navigator.getUserMedia) { // Standard
                    navigator.getUserMedia(mediaConfig, function(stream) {
                        video.src = stream;
                        video.play();
                    }, errBack);
                } else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
                    navigator.webkitGetUserMedia(mediaConfig, function(stream){
                        video.src = window.webkitURL.createObjectURL(stream);
                        video.play();
                    }, errBack);
                } else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
                    navigator.mozGetUserMedia(mediaConfig, function(stream){
                        video.src = window.URL.createObjectURL(stream);
                        video.play();
                    }, errBack);
                }


            }, false);


        </script>
    @endif--}}
@stop
