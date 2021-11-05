@extends('quiz::layout.app')

@section('page_title', $quiz->name)

@section('content')
    <style>
        a:hover{
            color: #fff !important;
        }

        ol.breadcrumb{
            color: #246EEC;
            background-color: #fff;
        }

        #first-info-user .row{
            background: white;
            margin-top: 10px;
            border-radius: 10px;
            align-items: center;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        }

        #first-info-user .header_right {
            text-align: center
        }

        #first-info-user .header_right .name_user{
            color: #14498a;
            font-weight: bold;
        }

        #second-name-quiz p{
            border-radius: 10px;
            color: white;
            background: #14498a;
            font-size: 16px;
            padding: 20px;
            font-weight: bold;
        }
        #three-info-quiz .three-info-quiz-1, #three-info-quiz .three-info-quiz-2{
            background: #D9D9D9;
            border-radius: 30px;
            padding: 20px;
            font-weight: bold;
            text-align: center;
        }
        #three-info-quiz h3 {
            color: #14498a;
        }

        #four-go-quiz .btn-go-quiz{
            background: #00AF50 !important;
        }
        #first-info-user .opts_account img{
            width: 100px;
            height: 90px;
            object-fit: cover
        }
        .content_quiz{
            margin-top: 18px !important
        }
        .icon_info_quiz {
            max-height: 80px
        }
    </style>
    <div id="page-navbar" class="clearfix">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="btn m-2">
                    <a itemprop="url" href="/" class="text-white">
                        Trang chủ
                    </a>
                </li>
                <li class="btn m-2">
                    <a itemprop="url" href="{{ route('module.quiz') }}" class="text-white"> Khảo thí </a>
                </li>
                <li class="btn m-2">
                    <span tabindex="0" class="text-white">{{ $quiz->name }}</span>
                </li>
            </ol>
        </nav>
        <div class="breadcrumb-button"></div>
    </div>

    <div class="row" style="margin-left: 10%; margin-right: 10%">
        <div class="col-12" id="first-info-user">
            <div class="row">
                <div class="col-6">
                    <img src="{{ image_file($quiz->img) }}" alt="" class="w-50">
                </div>
                <div class="col-6">
                    <div class="header_right">
                        <a href="javascript:void(0)" class="opts_account">
                            <img src="{{ $user_type == 1 ? \App\Profile::avatar() : asset('/images/user_tt.png') }}" alt="">
                        </a>
                        <ul>
                            <li class="mx-2 name_user pt-2">
                                <span>{{ $profile->name }}</span> <br>
                                <span>MSNV: {{ $profile->code }}</span> <br>
                            </li>
                        </ul>
                        <span class="mx-2 name_user">Email: {{ $profile->email }}</span> <br>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 p-0 text-center mt-3" id="second-name-quiz">
           <p>CHÀO MỪNG BẠN ĐÃ ĐẾN VỚI KỲ THI <br>{{ \Illuminate\Support\Str::upper($quiz->name) }}</p>
        </div>

        <div class="col-12 mt-3" id="three-info-quiz">
            <div class="row">
                <div class="col-12 col-md-6 info_quiz p-3">
                    <h3><i class="fa fa-comment"></i> LƯU Ý </h3>
                    <p class="text-black ml-4">
                        1. Thời gian bắt đầu tính khi bạn vào thi. <br>
                        2. Bài thi được lưu tự động khi hết giờ. <br>
                        3. Không quay trở lại khi đã bấm trả lời. <br>
                        4. Đọc kỹ đề bài trước khi trả lời. Chúc bạn thành công!
                    </p>
                    <h3 class="content_quiz"><i class="fa fa-briefcase"></i> NỘI DUNG</h3>
                    <p class="text-black ml-4">
                        @if(count($descriptions_quiz) > 0)
                            @foreach($descriptions_quiz as $description)
                                {{ $description }} <br>
                            @endforeach
                        @endif
                        <br>
                    </p>
                </div>
                <div class="col-12 col-md-6 px-3 info_test_quiz">
                    <div class="row mx-3 warpped_info">
                        <div class="col-6 info_test">
                            <div class="row m-0 w-100">
                                <div class="col-4 p-0">
                                    <img class="icon_info_quiz" src="{{ asset('images/web-03.png') }}" alt="" width="100%">
                                </div>
                                <div class="col-8 px-1">
                                    <h4 class="my-2">Số lượng câu hỏi</h4>
                                    <h3 class="mt-0">{{ $count_quiz_question }} </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 info_test">
                            <div class="row m-0 w-100">
                                <div class="col-4 p-0">
                                    <img class="icon_info_quiz" src="{{ asset('images/web-04.png') }}" alt="" width="100%">
                                </div>
                                <div class="col-8 px-1">
                                    <h4 class="my-2">Thời gian làm bài</h4>
                                    <h3 class="mt-0">{{ $quiz->limit_time .' '. trans('app.min') }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 info_test">
                            <div class="row m-0 w-100">
                                <div class="col-4 p-0">
                                    <img class="icon_info_quiz" src="{{ asset('images/web-05.png') }}" alt="" width="100%">
                                </div>
                                <div class="col-8 px-1">
                                    <h4 class="my-2">Điểm đạt</h4>
                                    <h3 class="mt-0">{{ $quiz->pass_score }}/{{ $quiz->max_score }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 info_test">
                            <div class="row m-0 w-100">
                                <div class="col-4 p-0">
                                    <img class="icon_info_quiz" src="{{ asset('images/web-06.png') }}" alt="" width="100%">
                                </div>
                                <div class="col-8 px-1">
                                    <h4 class="my-2">Số lần làm bài</h4>
                                    <h3 class="mt-0">{{ $quiz->max_attempts == 0 ? 'Không giới hạn' : $quiz->max_attempts }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mt-3 text-center" id="four-go-quiz">
            @if($can_create)
                <form action="{{ route('module.quiz.doquiz.create_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post" class="form-ajax">
                    <button type="submit" class="btn mt-2 btn-go-quiz" {{--id="go-quiz"--}}>
                        <h3><i class="fa fa-play-circle"></i> VÀO THI </h3>
                    </button>
                </form>
            @else
                <p><b>{{ data_locale('Bạn đã hết số lần làm bài cho kỳ thi này', 'You have run out of exams for this exam') }}</b></p>
            @endif
        </div>

        <div class="col-md-12 mt-3">
            <div id="history_quiz">
                <h4><button class="btn btn-primary"><i class="fa fa-list-ul"></i> LỊCH SỬ LÀM BÀI THI</button></h4>
                <table class="tDefault table table-hover bootstrap-table table-bordered" id="histories_quiz">
                    <thead>
                        <tr>
                            <th data-formatter="index_formatter" data-align="center">STT</th>
                            <th data-field="start_date">Thời gian bắt đầu</th>
                            <th data-field="end_date">Thời gian kết thúc</th>
                            <th data-field="grade" data-align="center">{{ trans('app.score') }}</th>
                            <th data-field="status" data-align="center">{{ trans('app.status') }}</th>
                            <th data-field="review" data-align="center" data-formatter="review_formatter">{{ trans('app.review') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="col-12 mt-3">
            <form action="{{ route('module.quiz.doquiz.user_review_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post" class="form-ajax">
                <div class="row mb-2">
                    <div class="col-8">
                        <h4><button class="btn btn-primary"><i class="fa fa-list-ul"></i> GÓP Ý SAU KHI THI</button></h4>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-primary w-30" type="submit" class="btn mt-2">Gửi</button>
                    </div>
                </div>
                <textarea name="content_review" id="" rows="5" class="form-control w-100" placeholder="Bạn có góp ý gì sau bài thi này?" required></textarea>
            </form>
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
        $(document).ready(function(){
            if(document.URL.indexOf("#")==-1){
                url = document.URL+"#";
                location = "#";
                location.reload(true);
            }
        });

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
