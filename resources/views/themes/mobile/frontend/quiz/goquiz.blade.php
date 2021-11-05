@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.quiz'))

@section('content')
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
                            <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#goquiz">
                                <i class="fa fa-edit"></i>
                                {{ data_locale('Vào làm bài thi', 'Into the test') }}
                            </a>
                        @else
                            <p><b>{{ data_locale('Bạn đã hết số lần làm bài cho kỳ thi này', 'You have run out of exams for this exam') }}</b></p>
                        @endif
                    </div>
                </div>
                <p></p>
                <h6>{{ trans('app.history_summary') }}</h6>
                <table class="tDefault table table-hover bootstrap-table table-bordered">
                    <thead>
                    <tr>
                        <th data-formatter="index_formatter" data-align="center">@lang('app.stt')</th>
                        <th data-formatter="info_formatter">@lang('app.info')</th>
                    </tr>
                    </thead>
                </table>
                <p></p>
                <h6>Góp ý</h6>
                <form action="{{ route('module.quiz_mobile.doquiz.user_review_quiz', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}" method="post" class="form-ajax">
                    <textarea name="content_review" id="" rows="5" class="form-control w-100" placeholder="Bạn có góp ý gì sau bài thi này?" required></textarea>
                    <button type="submit" class="btn mt-2 text-white">
                        Gửi
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade " id="goquiz" tabindex="-1" role="dialog" aria-hidden="true">
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
@endsection

@section('footer')
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function info_formatter(value, row, index) {
            return "{{ trans('app.start_date'). ': ' }}" + row.start_date + "<br> {{ trans('app.end_date') .': ' }}" + row.end_date + "<br> {{ trans('app.score') .': ' }}" + row.grade + "<br> {{ trans('app.status') .': ' }} " + row.status + "<br>" + ((row.after_review == 1 || row.closed_review == 1) ? '<a href="'+ row.review_link +'" class="btn btn-info text-white">Xem lại</a>' : '<span class="text-muted">Không được xem</span>');
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.quiz_mobile.doquiz.attempt_history', ['quiz_id' => $quiz->id, 'part_id' => $part->id]) }}',
        });
    </script>
@endsection
