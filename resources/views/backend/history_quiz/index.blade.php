@extends('layouts.backend')

@section('page_title', trans('backend.history'))

@php
    $tabs = Request::segment(4);
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
                        @can('quiz-history')
                            <a class="nav-item nav-link @if ($tabs == 'user')
                                active
                                @endif" id="nav-user-tab" href="{{ route('module.quiz.history_user') }}" >{{ trans('lacourse.internal_user_history') }}
                            </a>    
                        @endcan
                        
                        @can('quiz-history-user-second')
                            <a class="nav-item nav-link @if ($tabs == 'user-second')
                                active
                                @endif" id="nav-user-second-tab" href="{{ route('module.quiz.history_user_second') }}" >{{ trans('lacourse.external_user_history') }}
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
                        @switch(Request::segment(4))
                            @case('user')
                                @include('quiz::backend.history_user.index')
                                @break
                            @case('user-second')
                                @include('quiz::backend.history_user_second.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
