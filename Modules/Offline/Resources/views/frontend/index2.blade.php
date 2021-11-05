@extends('layouts.app')

@section('page_title', 'Khóa học tập trung')

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/online/css/list.css') }}">
    <style>
        .bookmark {
            text-transform: uppercase;
            position: absolute;
            top: 15px;
            right: -20px;
            z-index: 100;
            background: blue;
            transform: rotate(45deg);
            padding: 4px 25px;
            -webkit-transform-origin-y: bottom;
            transition: all 0.35s ease;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="slider-top">
            @foreach($sliders as $slider)
                <div>
                    <img src="{{ image_file($slider->image) }}" style="width: 100%;max-height: 450px" alt=""/>
                </div>
            @endforeach
        </div>
        <script>
            $(document).ready(function () {
                var sliders = $('.slider-top').bxSlider({
                    auto: false,
                    pager: false,
                });

                $('a.pager-prev').click(function () {
                    var current = sliders.getCurrentSlide();
                    sliders.goToPrevSlide(current) - 1;
                });

                $('a.pager-next').click(function () {
                    var current = sliders.getCurrentSlide();
                    sliders.goToNextSlide(current) + 1;
                });
            });
        </script>
        <div class="content-main" id="content-main">
            <form method="get" class="form-inline form-search">
                <div class="row content-fill">
                    <div class="col-md-6 text-left">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" autocomplete="off" placeholder="{{ trans('app.search') .' '. trans('app.course') }}">
                            <input type="text" name="fromdate" class="form-control datepicker" autocomplete="off" placeholder="{{ trans('app.start_date') }}">
                            <input type="text" name="todate" class="form-control datepicker" autocomplete="off" placeholder="{{ trans('app.end_date') }}">
                            <button class="btn btn-secondary btn-search" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-2">
                                <label class="name-fill mt-1">{{ trans('app.sort') }}: </label>
                            </div>
                            @php
                                $search_status = request()->get('status');
                                $search_training_program = request()->get('training_program_id');
                                $search_subject = request()->get('subject_id');
                            @endphp
                            <div class="col-sm-5">
                                <select name="training_program_id" class="form-control select2 load-training-program" data-placeholder="{{ trans('app.training_program') }}" onchange="submit()">
                                    <option value=""></option>
                                    @foreach($training_program as $item)
                                        <option value="{{ $item['id'] }}"
                                                @if($search_training_program == $item['id']) selected @endif>{{
                                            $item['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <select name="subject_id" class="form-control select2 load-subject" data-training-program="{{ $search_training_program }}" data-placeholder="{{ trans('app.subject') }}" onchange="submit()">
                                    <option value=""></option>
                                    @foreach($subject as $item)
                                        <option value="{{ $item['id'] }}"
                                                @if($search_subject == $item['id']) selected @endif>{{ $item['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <h4 class="title">{{ count($items) . ' ' . data_locale('Khóa học', 'Offline') }} <span class="red">{{ data_locale('Tập trung', 'Course') }}</span></h4>
            <div class="show">
                <div class="slider1">
                    <div class="row row1">
                        @foreach($items as $item)
                            @php
                                $status_course = $item->getStatusCourse();
                                $bookmark = $check_bookmarks($item->id, 2);
                            @endphp
                            <div class="col-md-3 block">
                                <div class="image" style="border-bottom: 5px solid {{ $status_course == 0 ? 'yellow' : ($status_course == 1 ? 'blue' : 'red') }}">
                                    <a href="{{ route('module.offline.detail', ['id' => $item->id]) }}">
                                        <img src="{{ image_file($item->image) }}"/>
                                        @if($item->isComplete())
                                            <div class="course course-complate"></div>
                                        @endif
                                    </a>
                                    @if($bookmark)
                                    <div class="bookmark"> {{ data_locale('Đánh dấu', 'Bookmark') }}</div>
                                    @endif
                                </div>

                                <div class="desc">
                                    <div class="name">{{ $item->name }}</div>
                                    <p></p>
                                    <div class="btn-views">
                                        <a href="{{ route('module.offline.detail', ['id' => $item->id]) }}" class="btn btn-success btn-sm">{{ trans('app.see_detail') }}</a>
                                    </div>

                                    @php
                                        $status = $item->getStatusRegister();
                                        $text = $text_status($status);
                                    @endphp
                                    @if($status == 1)
                                        <div class="btn-views">
                                            <form action="{{ route('module.offline.register_course', ['id' => $item->id]) }}" method="post" class="form-ajax">
                                                <button type="submit" class="btn btn-success btn-sm">{{ $text }}</button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="btn-views">
                                            <a href="javascript:void(0)" class="btn btn-{{ $class_status($status) }} btn-sm">{{ $text }}</a>
                                        </div>
                                    @endif

                                    <div class="btn-views">
                                        @if($bookmark)
                                            <form action="{{ route('frontend.home.remove_course_bookmark', ['course_id' => $item->id, 'course_type' => 2, 'my_course'=> 0]) }}" method="post" class="form-ajax">
                                                <button type="submit" class="btn btn-info btn-sm">{{ data_locale('Bỏ đánh dấu', 'Unmark') }}</button>
                                            </form>
                                        @else
                                            <form action="{{ route('frontend.home.save_course_bookmark', ['course_id' => $item->id, 'course_type' => 2, 'my_course' => 0]) }}" method="post" class="form-ajax">
                                                <button type="submit" class="btn btn-primary btn-sm">{{ data_locale('Đánh dấu', 'Bookmark') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="user">
                                    <div class="icon-user">
                                        {{ $item->countUserRegister() }} <i class="fa fa-user"></i>
                                    </div>
                                </div>

                                <div class="title" style="height: 200px">
                                    <a href="{{ route('module.offline.detail', ['id' => $item->id]) }}"> {{ $item->name }} </a>
                                    <div class="object">
                                        <span class="red">
                                            @if($item->getObject())
                                                {{ trans('app.trainee_object') }}:
                                            @endif
                                        </span>
                                        {{ sub_char($item->getObject()) }}
                                    </div>
                                    <div class="time">{{ trans('app.time') .': '. get_date($item->start_date) }}
                                        @if($item->end_date) {{ trans('app.to') .' '. get_date($item->end_date) }} @endif
                                    </div>
                                    @if($item->register_deadline)
                                        <div class="deadline">{{ trans('app.register_deadline') }}: <b>{{ get_date($item->register_deadline) }}</b></div>
                                    @endif
                                </div>
                                <hr style="margin: 0; border-top: 1px solid #ddd;">
                                <div class="footer-block">
                                    @if($status_course == 0)
                                        <img src="{{ asset('styles/images/icon-ch.png') }}" alt=""/> {{ trans('app.not_learned') }}
                                    @elseif($status_course == 1)
                                        <img src="{{ asset('styles/images/icon-dahoc.png') }}" alt=""/> {{ trans('app.completed') }}
                                    @elseif($status_course == 2)
                                        <img src="{{ asset('styles/images/icon-dh.png') }}" alt=""/> {{ trans('app.is_learning') }}
                                    @elseif($status_course == 3)
                                        <img src="{{ asset('styles/images/ion_lock.png') }}" alt="" width="20px"> {{ trans('app.ended') }}
                                    @endif
                                    <img src="{{ asset('styles/images/sao-sang.png') }}"/> {{ $item->avgRatingStar() .' ('. $item->countRatingStar() .' '. trans('app.votes') .')' }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row justify-content-end">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
