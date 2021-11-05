@extends('layouts.backend')

@section('page_title', trans('backend.history'))

@php
    $tabs = Request::segment(2);
@endphp
@section('header')
    <style>
        #my-course .tab_crse .nav-link{
            padding: 0.5rem 0.5rem !important;
        }

        #training-by-title .img-info{
            width: 30px;
        }
        #training-by-title .progress{
            border-radius: 1rem !important;
        }
        #training-by-title .progress2{
            height: 2rem !important;
        }
    </style>
@endsection
@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="course_tabs" id="my-course">
                <nav>
                    <div class="nav nav-pills mb-4 tab_crse" id="nav-tab" role="tablist">
                        @can('model-history')
                            <a class="nav-item nav-link @if ($tabs == 'model-history')
                                active
                                @endif" id="nav-model-history-tab" href="{{ route('module.modelhistory.index') }}" >{{ trans('lamanager.update_history') }}
                            </a>
                        @endcan
                        
                        <a class="nav-item nav-link @if ($tabs == 'login-history')
                            active
                            @endif" id="nav-login-history-tab" href="{{ route('backend.login-history') }}" >{{ trans('lamanager.login_history') }}
                        </a>
                        
                        @can('log-view-course')
                            <a class="nav-item nav-link @if ($tabs == 'log-view-course')
                                active
                                @endif" id="nav-log-view-course-tab" href="{{ route('module.log.view.course.index') }}">{{ trans('lamanager.courseview_history') }}
                            </a>
                        @endcan
                    </div>
                </nav>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="course_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        @switch(Request::segment(2))
                            @case('model-history')
                                @include('modelhistory::backend.index')
                                @break
                            @case('login-history')
                                @include('backend.login_history.index')
                                @break
                            @case('log-view-course')
                                @include('logviewcourse::backend.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
