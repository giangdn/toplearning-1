@extends('layouts.app')

@section('page_title', __('app.quiz'))

@section('content')

    <div id="quiz-list">
        <div class="sa4d25">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="_14d25">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="explore_search">
                                        <div class="ui search focus">
                                            <div class="ui left icon input swdh11">
                                                <input class="prompt srch_explore" type="text" placeholder="Search for Tuts Videos, Tutors, Tests and more..">
                                                <i class="uil uil-search-alt icon icon2"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="_14d25">
                                        <div class="row">
                                            @foreach($quizs as $item)
                                                <div class="col-lg-3 col-md-4">
                                                    <div class="fcrse_1 mt-30 library">
                                                        <div class="fcrse_img">
                                                            <img alt="{{ $item->quiz_name }}" class="lazy" data-src="{{ image_library($item->image) }}">
                                                            <div class="course-overlay">
                                                            </div>
                                                        </div>
                                                        <div class="fcrse_content">
                                                            <label class="crse14s">{{ $item->quiz_name }}</label>
                                                            <div class="vdtodt">
                                                                <div class="timer" on="timer()" data-time="{{ \Carbon\Carbon::parse($item->end_date)->format('H:i d/m/Y') }}">
                                                                    <ul>
                                                                        <li><span id="days"></span>days</li>
                                                                        <li><span id="hours"></span>Hours</li>
                                                                        <li><span id="minutes"></span>Minutes</li>
                                                                        <li><span id="seconds"></span>Seconds</li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <a href="{{ route('module.quiz.doquiz.index', ['quiz_id' => $item->id, 'part_id' => $item->part_id]) }}" class="btn btn-info btn-sm">{{ trans('app.goquiz') }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function timer(){
            const second = 1000,
                minute = second * 60,
                hour = minute * 60,
                day = hour * 24;

            let countDown = new Date('Sep 30, 2020 00:00:00').getTime(),
                x = setInterval(function() {
                    let now = new Date().getTime(),
                        distance = countDown - now;

                    document.getElementById('days').innerText = Math.floor(distance / (day)),
                        document.getElementById('hours').innerText = Math.floor((distance % (day)) / (hour)),
                        document.getElementById('minutes').innerText = Math.floor((distance % (hour)) / (minute)),
                        document.getElementById('seconds').innerText = Math.floor((distance % (minute)) / second);

                    //do something later when date is reached
                    //if (distance < 0) {
                    //  clearInterval(x);
                    //  'IT'S MY BIRTHDAY!;
                    //}

                }, second)
        }

        $('.timer').timer();

    </script>
@stop
