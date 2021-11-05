@extends('layouts.backend')

@section('page_title', $page_title)
@section('header')
<link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
<script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
@endsection
@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
@php
$tabs = request()->get('tabs', null);
$modulePromotion = array_key_exists('Promotion',Module::allEnabled());
@endphp
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.online.management') }}">{{ trans('backend.online_course') }}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    <div class="row">
        @if($model->id)
        <div class="col-md-12 text-center">
            @can('online-course-register')
                <a href="{{ route('module.online.register', [$model->id]) }}" class="btn btn-info">
                    <div><i class="fa fa-edit"></i></div>
                    <div>Ghi danh nội bộ</div>
                </a>

                <a href="{{ route('module.online.register_secondary', [$model->id]) }}" class="btn btn-info">
                    <div><i class="fa fa-edit"></i></div>
                    <div>Ghi danh bên ngoài</div>
                </a>
            @endcan

            @if(!$user_invited)
                @can('online-course-result')
                <a href="{{ route('module.online.result', [$model->id]) }}" class="btn btn-info">
                    <div><i class="fa fa-briefcase"></i></div>
                    <div>{{ trans('backend.training_result') }}</div>
                </a>
                @endcan

                <a href="{{ route('module.online.quiz', [$model->id]) }}" class="btn btn-info">
                    <div><i class="fa fa-question-circle"></i></div>
                    <div>{{ trans('backend.quiz_list') }}</div>
                </a>

                @can('online-course-rating-level-result')
                    <a href="{{ route('module.online.rating_level.list_report', [$model->id]) }}" class="btn btn-info">
                        <div><i class="fa fa-star"></i></div>
                        <div>Kết quả đánh giá</div>
                    </a>
                @endcan
            @endif
        </div>
        @endif
    </div>
    <br>
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item">
                <a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a>
            </li>

            @if($model->id && userCan(['online-course-create', 'online-course-edit']) && !$user_invited)
                <li class="nav-item">
                    <a href="#object" class="nav-link @if($tabs == 'object') active @endif" data-toggle="tab">
                        {{ trans('backend.object_join') }}
                    </a>
                </li>
                <li class="nav-item"><a href="#tutorial" class="nav-link" data-toggle="tab">Hướng dẫn học</a></li>
                <li class="nav-item"><a href="#cost" class="nav-link @if($tabs == 'cost') active @endif" data-toggle="tab">{{ trans('backend.training_cost') }}</a></li>
                <li class="nav-item"><a href="#activity-lesson" class="nav-link @if($tabs == 'activity') active @endif" data-toggle="tab">Các hoạt động / Bài học</a></li>
                <li class="nav-item"><a href="#image-activity" class="nav-link" data-toggle="tab">Ảnh đại diện hoạt động</a></li>
                <li class="nav-item"><a href="#condition" class="nav-link @if($tabs == 'condition') active @endif" data-toggle="tab">{{ trans('backend.conditions') }}</a></li>
                <li class="nav-item"><a href="#history" class="nav-link @if($tabs == 'history') active @endif" data-toggle="tab">{{ trans('backend.history') }}</a></li>
                <li class="nav-item"><a href="#libraryFile" class="nav-link" data-toggle="tab">Quản lý / Thư viện file</a></li>
                <li class="nav-item"><a href="#note-evaluate" class="nav-link" data-toggle="tab">Học viên ghi chép / Đánh giá</a></li>
                <li class="nav-item"><a href="#ask-answer" class="nav-link" data-toggle="tab">Hỏi / Đáp</a></li>
                @if(Module::has('Promotion') && $modulePromotion)
                <li class="nav-item"><a href="#promotion" class="nav-link @if($tabs == 'promotion') active @endif" data-toggle="tab">{{ trans('backend.reward_points') }}</a></li>
                @endif
                <li class="nav-item"><a href="#setting_percent" class="nav-link" data-toggle="tab">Thiết lập trọng số</a></li>
                <li class="nav-item"><a href="#rating_level" class="nav-link @if($tabs == 'rating_level') active @endif" data-toggle="tab">Đánh giá 4 cấp độ</a></li>
                <li class="nav-item"><a href="#rating_course" class="nav-link @if($tabs == 'rating_course') active @endif" data-toggle="tab">Đánh giá Khóa học</a></li>
            @endif

        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                @include('online::backend.online.form.info')
            </div>
            @if($model->id && !$user_invited)
                <div id="object" class="tab-pane @if($tabs == 'object') active @endif">
                    @include('online::backend.online.form.object')
                </div>
                <div id="cost" class="tab-pane @if($tabs == 'cost') active @endif">
                    @include('online::backend.online.form.cost')
                </div>
                <div id="activity-lesson" class="tab-pane @if($tabs == 'activity') active @endif">
                    @include('online::backend.online.form.activity')
                </div>
                <div id="image-activity" class="tab-pane @if($tabs == 'activity') active @endif">
                    @include('online::backend.online.form.image_activity')
                </div>
                <div id="condition" class="tab-pane @if($tabs == 'condition') active @endif">
                    @include('online::backend.online.form.condition')
                </div>
                <div id="history" class="tab-pane @if($tabs == 'history') active @endif">
                    @include('online::backend.online.form.history')
                </div>
                <div id="libraryFile" class="tab-pane">
                    @include('online::backend.online.form.libraryFile')
                </div>
                <div id="note-evaluate" class="tab-pane">
                    @include('online::backend.online.form.note_evaluate')
                </div>
                <div id="tutorial" class="tab-pane">
                    @include('online::backend.online.form.tutorial')
                </div>
                <div id="ask-answer" class="tab-pane">
                    @include('online::backend.online.form.ask_answer')
                </div>
                @if(Module::has('Promotion') && $modulePromotion)
                    @if(View::exists('online::backend.online.form.promotion'))
                        <div id="promotion" class="tab-pane @if($tabs == 'promotion') active @endif">
                            @include('online::backend.online.form.promotion')
                        </div>
                    @endif
                @endif
                <div id="setting_percent" class="tab-pane">
                    @include('online::backend.online.form.setting_percent')
                </div>
                <div id="rating_level" class="tab-pane @if($tabs == 'rating_level') active @endif">
                    @include('online::backend.online.form.rating_level')
                </div>
                <div id="rating_course" class="tab-pane @if($tabs == 'rating_course') active @endif">
                    @include('online::backend.online.form.rating_course')
                </div>
            @endif

        </div>
    </div>

    <script type="text/javascript">
        var ajax_get_course_code = "{{ route('module.online.ajax_get_course_code') }}";
    </script>
    <script src="{{ asset('styles/module/online/js/online.js') }}"></script>
</div>
@stop
