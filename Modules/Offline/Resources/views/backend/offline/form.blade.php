@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
<script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
<style>
    table tbody th {
        font-weight: normal !important;
    }
</style>
<link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
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
        <a href="{{ route('module.offline.management') }}">{{ trans('backend.offline_course') }}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    <div class="row">
        @if($model->id)
        <div class="col-md-12 text-center">
            @canany(['offline-course-register'])
            <a href="{{ route('module.offline.register', ['id' => $model->id]) }}" class="btn btn-info">
                <div><i class="fa fa-edit"></i></div>
                <div>{{ trans('backend.register') }}</div>
            </a>
            @endcanany
            @if(!$user_invited)
                @canany(['offline-course-teacher'])
                <a href="{{ route('module.offline.teacher', ['id' => $model->id]) }}" class="btn btn-info">
                    <div><i class="fa fa-inbox"></i></div>
                    <div>{{ trans('backend.teacher') }}</div>
                </a>
                @endcanany
                <a href="{{ route('module.offline.monitoring_staff', ['id' => $model->id]) }}" class="btn btn-info">
                    <div><i class="fa fa-user"></i></div>
                    <div>Cán bộ theo dõi</div>
                </a>
                @canany(['offline-course-attendance'])
                <a href="{{ route('module.offline.attendance', ['id' => $model->id]) }}" class="btn btn-info">
                    <div><i class="fa fa-user"></i></div>
                    <div>{{ trans('backend.attendance') }}</div>
                </a>
                @endcanany
                @canany(['offline-course-result'])
                <a href="{{ route('module.offline.result', ['id' => $model->id]) }}" class="btn btn-info">
                    <div><i class="fa fa-briefcase"></i></div>
                    <div>{{ trans('backend.training_result') }}</div>
                </a>
                @endcanany
                {{--@can('offline-course-rating-result')
                <a href="{{ route('module.rating.result.index', ['course_id' => $model->id, 'type' => 2]) }}" class="btn btn-info">
                    <div><i class="fa fa-star"></i></div>
                    <div>{{ trans('backend.result_of_evaluation') }}</div>
                </a>
                @endcan--}}
                @can('offline-course-rating-level-result')
                    <a href="{{ route('module.offline.rating_level.list_report', [$model->id]) }}" class="btn btn-info">
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
            <li class="nav-item"><a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            @if($model->id && userCan(['offline-course-create', 'offline-course-edit']) && !$user_invited)
                <li class="nav-item"><a href="#object" class="nav-link @if($tabs == 'object') active @endif" role="tab" data-toggle="tab">{{ trans('backend.object_join') }}</a></li>
                <li class="nav-item"><a href="#schedule" class="nav-link @if($tabs == 'schedule') active @endif" role="tab" data-toggle="tab">{{ trans('backend.schedule') }}</a></li>
                <li class="nav-item"><a href="#cost" class="nav-link @if($tabs == 'cost') active @endif" role="tab" data-toggle="tab">{{ trans('backend.training_cost') }}</a></li>
                <li class="nav-item"><a href="#cost_student" class="nav-link @if($tabs == 'cost_student') active @endif" role="tab" data-toggle="tab">{{ trans('backend.student_cost') }}</a></li>
                <li class="nav-item"><a href="#condition" class="nav-link @if($tabs == 'condition') active @endif" role="tab" data-toggle="tab">{{ trans('backend.conditions') }}</a></li>
                <li class="nav-item"><a href="#history" class="nav-link @if($tabs == 'history') active @endif" role="tab" data-toggle="tab">{{ trans('backend.history') }}</a></li>
                <li class="nav-item"><a href="#upload" class="nav-link @if($tabs == 'upload') active @endif" role="tab" data-toggle="tab">Quản lý file</a></li>
                <li class="nav-item"><a href="#libraryFile" class="nav-link @if($tabs == 'libraryFile') active @endif" role="tab" data-toggle="tab">Thư viện file</a></li>
                @if(Module::has('Promotion') && $modulePromotion)
                    <li class="nav-item"><a href="#promotion" class="nav-link @if($tabs == 'promotion') active @endif" role="tab" data-toggle="tab">{{ trans('backend.reward_points') }}</a></li>
                @endif
                <li class="nav-item"><a href="#rating_level" class="nav-link @if($tabs == 'rating_level') active @endif" data-toggle="tab">Đánh giá 4 cấp độ</a></li>
                <li class="nav-item"><a href="#ratting_course" class="nav-link @if($tabs == 'ratting_course') active @endif" data-toggle="tab">Đánh giá Khóa học</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                @include('offline::backend.offline.form.info')
            </div>
            @if($model->id && !$user_invited)
                <div id="object" class="tab-pane @if($tabs == 'object') active @endif">
                    @include('offline::backend.offline.form.object')
                </div>
                <div id="cost" class="tab-pane @if($tabs == 'cost') active @endif">
                    @include('offline::backend.offline.form.cost')
                </div>
                <div id="cost_student" class="tab-pane @if($tabs == 'cost_student') active @endif">
                    @include('offline::backend.offline.form.cost_student')
                </div>
                <div id="condition" class="tab-pane @if($tabs == 'condition') active @endif">
                    @include('offline::backend.offline.form.condition')
                </div>
                <div id="schedule" class="tab-pane @if($tabs == 'schedule') active @endif">
                    @include('offline::backend.offline.form.schedule_parent')
                </div>
                <div id="history" class="tab-pane @if($tabs == 'history') active @endif">
                    @include('offline::backend.offline.form.history')
                </div>
                <div id="upload" class="tab-pane @if($tabs == 'upload') active @endif">
                    @include('offline::backend.offline.form.uploadFile')
                </div>
                <div id="libraryFile" class="tab-pane @if($tabs == 'libraryFile') active @endif">
                    @include('offline::backend.offline.form.libraryFile')
                </div>
                @if(Module::has('Promotion') && $modulePromotion)
                    @if(View::exists('offline::backend.offline.form.promotion'))
                        <div id="promotion" class="tab-pane @if($tabs == 'promotion') active @endif">
                            @include('offline::backend.offline.form.promotion')
                        </div>
                    @endif
                @endif
                <div id="rating_level" class="tab-pane @if($tabs == 'rating_level') active @endif">
                    @include('offline::backend.offline.form.rating_level')
                </div>
                <div id="ratting_course" class="tab-pane @if($tabs == 'ratting_course') active @endif">
                    @include('offline::backend.offline.form.ratting_course')
                </div>
            @endif
        </div>
    </div>
    <script type="text/javascript">
        // ClassicEditor.create( document.querySelector( '.editor' ) )
        // .then( editor => {} )
        // .catch( error => {
        //     console.error(error);
        // } );
        var ajax_get_course_code = "{{ route('module.offline.ajax_get_course_code') }}";
    </script>
    <script src="{{ asset('styles/module/offline/js/offline.js') }}"></script>
</div>
@stop
