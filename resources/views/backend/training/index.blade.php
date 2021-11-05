@extends('layouts.backend')

@section('page_title', trans('backend.training_organizations'))

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
                        @can('online-course')
                            <a class="nav-item nav-link @if ($tabs == 'online')
                                active
                                @endif" id="nav-online-tab" href="{{ route('module.online.management') }}" >{{ trans('lamanager.online_course') }}
                            </a>    
                        @endcan
                        
                        @can('offline-course')
                            <a class="nav-item nav-link @if ($tabs == 'offline')
                                active
                                @endif" id="nav-offline-tab" href="{{ route('module.offline.management') }}" >{{ trans('backend.offline_course') }}
                            </a>   
                        @endcan
                        
                        @can('training-plan')
                            <a class="nav-item nav-link @if ($tabs == 'training-plan')
                                active
                                @endif" id="nav-training-plan-tab" href="{{ route('module.training_plan') }}">{{ trans('backend.training_plan') }}
                            </a>    
                        @endcan
                        
                        @can('course-plan')
                            <a class="nav-item nav-link @if ($tabs == 'course-plan')
                                active
                                @endif" id="nav-course-plan-tab" href="{{ route('module.course_plan.management') }}">{{ trans('lamanager.month_elearning_plan') }}
                            </a>    
                        @endcan
                        
                        @can('course-old')
                            <a class="nav-item nav-link @if ($tabs == 'courseold')
                                active
                                @endif" id="nav-courseold-tab" href="{{ route('module.courseold') }}">{{ trans('backend.course_old') }}
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
                            @case('online')
                                @include('online::backend.online.index')
                                @break
                            @case('offline')
                                @include('offline::backend.offline.index')
                                @break
                            @case('training-plan')
                                @include('trainingplan::backend.plan.index')
                                @break
                            @case('course-plan')
                                @include('courseplan::backend.index')
                                @break
                            @case('courseold')
                                @include('courseold::index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
