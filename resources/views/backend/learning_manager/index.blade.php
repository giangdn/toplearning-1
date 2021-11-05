@extends('layouts.backend')

@section('page_title', trans('lacourse.learning_manager'))

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
                        @can('training-roadmap')
                            <a class="nav-item nav-link @if ($tabs == 'trainingroadmap')
                                active
                                @endif" id="nav-trainingroadmap-tab" href="{{ route('module.trainingroadmap') }}" >{{ trans('backend.trainingroadmap') }}
                            </a>    
                        @endcan
                        
                        @can('training-by-title')
                            <a class="nav-item nav-link @if ($tabs == 'training-by-title')
                                active
                                @endif" id="nav-training-by-title-tab" href="{{ route('module.training_by_title') }}" >{{ trans('lacourse.learning_path') }}
                            </a>    
                        @endcan
                        
                        @can('training-by-title-result')
                            <a class="nav-item nav-link @if ($tabs == 'training-by-title-result')
                                active
                                @endif" id="nav-training-by-title-result-tab" href="{{ route('module.training_by_title.result') }}">{{ trans('lacourse.learning_path_result') }}
                            </a>    
                        @endcan
                        
                        @can('mergesubject')
                            <a class="nav-item nav-link @if ($tabs == 'mergesubject')
                                active
                                @endif" id="nav-mergesubject-tab" href="{{ route('module.mergesubject.index') }}">{{ trans('backend.merge_subject') }}
                            </a>   
                        @endcan
                        
                        @can('splitsubject')
                            <a class="nav-item nav-link @if ($tabs == 'splitsubject')
                                active
                                @endif" id="nav-splitsubject-tab" href="{{ route('module.splitsubject.index') }}">{{ trans('backend.split_subject') }}
                            </a>    
                        @endcan
                        
                        @can('subjectcomplete')
                            <a class="nav-item nav-link @if ($tabs == 'subjectcomplete')
                                active
                                @endif" id="nav-subjectcomplete-tab" href="{{ route('module.subjectcomplete.index') }}">{{ trans('backend.subject_complete') }}
                            </a>    
                        @endcan
                        
                        @can('movetrainingprocess')
                            <a class="nav-item nav-link @if ($tabs == 'movetrainingprocess')
                                active
                                @endif" id="nav-movetrainingprocess-tab" href="{{ route('module.movetrainingprocess.index') }}">{{ trans('backend.move_training_process') }}
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
                            @case('trainingroadmap')
                                @include('trainingroadmap::index.index')
                                @break
                            @case('training-by-title')
                                @include('trainingbytitle::backend.training_by_title.index')
                                @break
                            @case('training-by-title-result')
                                @include('trainingbytitle::backend.training_by_title_result.index')
                                @break
                            @case('mergesubject')
                                @include('mergesubject::backend.index')
                                @break
                            @case('splitsubject')
                                @include('splitsubject::backend.index')
                                @break
                            @case('subjectcomplete')
                                @include('subjectcomplete::backend.index')
                                @break
                            @case('movetrainingprocess')
                                @include('movetrainingprocess::backend.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
