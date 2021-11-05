@extends('layouts.app')

@section('page_title', 'Danh sách các bài thi')
@section('header')
    <style>
        .datepicker {
            box-sizing: border-box;
        }
    </style>
@endsection

@section('content')

    <div id="quiz-list">
        <div class="container-fluid">
            <form action="{{ route('module.quiz') }}" method="get" id="form-search" class="mt-30">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-2">
                                <input type="text" name="fromdate" class="form-control datepicker" placeholder="{{ trans('app.start_date') }}"/>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="todate" class="form-control datepicker" placeholder="{{ trans('app.end_date') }}"/>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="search" class="form-control" placeholder="{{ trans('app.name_exams') }}">
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control select2 w-100" name="quiz_type" data-placeholder="{{ trans('app.quiz_type') }}">
                                    <option value=""></option>
                                    @foreach ($quiz_types as $quiz_type)
                                        <option value="{{ $quiz_type->id }}">{{ $quiz_type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2 input-group-btn">
                                <button class="btn btn-info btn-search-quiz" type="submit"><i class="glyphicon glyphicon-search"></i> {{ trans('app.search') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <br />
            <div class="row" id="list-quiz">
                @foreach ($quizs as $quiz)
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-3">
                                        <img src="{{ asset('images/dashboard/online-course.svg') }}" alt="" class="w-100">
                                    </div>
                                    <div class="quiz_card_name col-9">
                                        <div class="quiz_p_name mb-0">
                                            <span>{{ $quiz->quiz_name }}</span> 
                                            <div class="show_quiz_name w-100">
                                                {{ $quiz->quiz_name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <img class="card-img-top" src="{{ $quiz->image }}" alt="Card image cap" style="object-fit: cover" height="200px">
                            <div class="card-body text-success">
                                <input type="hidden" class="quiz_id" value="{{ $quiz->id }}">
                                <input type="hidden" class="time_quiz_{{$quiz->id}}" value="{{ $quiz->time_quiz }}">
                                <input type="hidden" class="count_downt_quiz_{{$quiz->id}}" value="{{ $quiz->count_downt }}">
                                <p class="card-text">Bắt đầu: {{ $quiz->start_date }}</p>
                                <p class="card-text">Kết thúc: {{ $quiz->end_date ? $quiz->end_date : '' }}</p>
                                <p class="card-text">Trạng thái: {!! $quiz->status !!}</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="row">
                                            <div class="col-5 icon_timestop">
                                                <img src="{{ asset('images/stopwatch.png') }}" alt="" width="25px">
                                            </div>
                                            <div class="col-7 count_down_time_{{$quiz->id}} pl-0 time_text_count_down">
                                                <p class="count_down_{{$quiz->id}}"></p>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="go_quiz col-7">
                                        {!! $quiz->link !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($check_search == 0)
            {{ $quizs->links() }}
            @endif
        </div>
    </div>

    <script type="text/javascript">
        // var d = new Date({{ date('Y') }},{{ date('m') }},{{ date('d') }},{{ date('H') }},{{ date('i') }},{{ date('s') }});
        // setInterval(function () {
        //     d.setSeconds(d.getSeconds() + 1);
        //     $('#hours').text(pad(d.getHours(), 2));
        //     $('#min').text(pad(d.getMinutes(), 2));
        //     $('#sec').text(pad(d.getSeconds(), 2));
        // }, 1000);

        function pad(num, size) {
            var s = "000000000" + num;
            return s.substr(s.length - size);
        }

        $('#list-quiz').on('click', '.notify-goquiz', function () {
            show_message('Kỳ thi chưa tới giờ', 'warning');
        });

        $('#list-quiz').on('click', '.note-quiz', function () {
            var quiz_id = $(this).data('quiz_id');
            $('#quiz_id').val(quiz_id);
            $('#noteModal').modal();
        });

        window.onload = countDowntQuiz;
        function countDowntQuiz() {
            $(".quiz_id").each(function() {
                var id = $(this).val();
                var time_quiz = $('.time_quiz_'+id).val();
                
                if (time_quiz == 0) {
                    var now = new Date("{{ date('Y-m-d H:i:s') }}");
                    setInterval(function () {
                        var quiz_id = id;
                        var count_down = $('.count_downt_quiz_'+quiz_id).val();
                        var count_time = moment(new Date()).format("YYYY-MM-DD HH:mm:ss")
                        now.setSeconds(now.getSeconds() + 1);

                        var distance = new Date(count_down) - now;

                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

                        var time = pad(hours, 2) + ':' + pad(minutes, 2);
                        console.log(count_time);
                        $('.count_down_'+quiz_id).html(time);
                        if(count_time == count_down) {
                            location.reload();
                        }
                    }, 1000);
                } else {
                    $('.count_down_'+id).html('00:00');
                }
            });
        }
    </script>
@stop
