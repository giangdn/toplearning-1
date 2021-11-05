<!doctype html>
<html lang="en" class="deeppurple-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-language" content="en">
    <meta name="language" content="en">
    <title>@yield('page_title')</title>
    <link href="{{ asset('css/font_Roboto_300_400_500_700_display_swap.css') }}" rel="stylesheet">
    <!-- Favicon Icon -->
    <link rel="icon" type="image/png" href="{{ image_file(\App\Config::getFavicon()) }}">
    <link href="{{ asset('themes/mobile/vendor/materializeicon/material-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/bootstrap-4.4.1/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/swiper/css/swiper.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/OwlCarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/OwlCarousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/fullcalendar/main.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/bootstrap-table/bootstrap-table.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

    <link href="{{ asset('themes/mobile/vendor/emojionearea/css/emojionearea.min.css') }}" rel="stylesheet">

    <link href="{{ asset('themes/mobile/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/mobile/css/dropzone.css') }}" rel="stylesheet">
    <link href="https://vjs.zencdn.net/7.8.4/video-js.css" rel="stylesheet"/>
    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
    <script src="{{ asset('themes/mobile/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('themes/mobile/js/jquery-ui.js') }}"></script>

    <script type="text/javascript">
        var base_url = '{{ url('/') }}';
    </script>

    <style>
        .card-text, .card-title{
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card quiz">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ trans('app.exam_info') }}</h5>
                        <p class="card-text">
                            {{ data_locale('Số lần thi cho phép : ', 'The number of times allowed : ') }}
                            @if($quiz->max_attempts > 0)
                                {{ $quiz->max_attempts .' '. trans('app.times') }}
                            @else
                                {{ trans('app.unlimited') }}
                            @endif
                        </p>
                        <p class="card-text">
                            {{ data_locale('Kỳ thi được mở lúc', 'Exam is open at') .': '. get_date($part->start_date, 'H:i d/m/Y') }}
                        </p>
                        @if($part->end_date)
                            <p class="card-text">
                                {{ data_locale('Kỳ thi sẽ đóng lúc', 'Exam will close at').': '.get_date($part->end_date, 'H:i d/m/Y') }}
                            </p>
                        @endif
                        <p class="card-text">{{ trans('app.time_exam') .': '. $quiz->limit_time .' '. trans('app.min') }}</p>

                        @if($can_create)
                            <a href="javascript:void(0)" class="btn btn-primary" {{--data-toggle="modal" data-target="#goquiz"--}} id="goquiz">
                                <i class="fa fa-edit"></i>
                                {{ data_locale('Vào làm bài thi', 'Into the test') }}
                            </a>
                        @else
                            <p><b>{{ data_locale('Bạn đã hết số lần làm bài cho kỳ thi này', 'You have run out of exams for this exam') }}</b></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" {{--id="goquiz"--}} tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('module.quiz_mobile.doquiz.create_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post" class="form-ajax">
                    @csrf
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

    <script>
        $('#goquiz').on('click', function () {
            $.ajax({
                type: 'POST',
                url: "{{ route('module.quiz_mobile.doquiz.create_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}",
                dataType: 'json',
                data: {},
            }).done(function(data) {
                window.location = (data.redirect);

                return false;
            }).fail(function(data) {
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        })
    </script>

    <script src="{{ asset('themes/mobile/js/popper.min.js') }}"></script>
    <script src="{{ asset('themes/mobile/vendor/bootstrap-table/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('themes/mobile/vendor/bootstrap-table/bootstrap-table-vi-VN.js') }}"></script>
    <script src="{{ asset('themes/mobile/js/LoadBootstrapTable.js') }}"></script>
    <script src="{{ asset('themes/mobile/js/load-ajax.js') }}"></script>
    <script src="{{ asset('themes/mobile/js/form-ajax.js') }}"></script>
    <script src="{{ asset('themes/mobile/js/moment.min.js') }}"></script>

    <script src="{{ asset('themes/mobile/vendor/bootstrap-4.4.1/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('themes/mobile/vendor/sweetalert2/sweetalert2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('themes/mobile/vendor/fullcalendar/main.js') }}" type="text/javascript"></script>
    <script src="{{ asset('themes/mobile/vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('themes/mobile/vendor/OwlCarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('themes/mobile/vendor/swiper/js/swiper.min.js') }}"></script>
    <script src="{{ asset('themes/mobile/vendor/cookie/jquery.cookie.js') }}"></script>
    <script src="{{ asset('themes/mobile/vendor/emojionearea/js/emojionearea.min.js') }}"></script>
    <script src="{{ asset('themes/mobile/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
    <script src="{{ asset('themes/mobile/vendor/bootstrap-datetimepicker/js/load-datetimepicker.js') }}"></script>

    <script src="{{ asset('themes/mobile/js/load-select2.js') }}"></script>
    <script src="{{ asset('themes/mobile/js/main.js') }}"></script>
    <script src="{{ asset('themes/mobile/js/dropzone.js') }}"></script>
</body>
</html>
