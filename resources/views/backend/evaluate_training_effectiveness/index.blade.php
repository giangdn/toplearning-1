@extends('layouts.backend')

@section('page_title', trans('backend.plan_app'))

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
                        @can('rating-template')
                            <a class="nav-item nav-link @if ($tabs == 'evaluationform')
                                active
                                @endif" id="nav-evaluationform-tab" href="{{ route('module.rating.template') }}" >{{ trans('lacourse.template_rate') }}
                            </a>    
                        @endcan
                        
                        @can('rating-levels')
                            <a class="nav-item nav-link @if ($tabs == 'rating-organization')
                                active
                                @endif" id="nav-rating-organization-tab" href="{{ route('module.rating_organization') }}" >{{ trans('lacourse.rating_organization') }}
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
                            @case('evaluationform')
                                @include('rating::backend.template.index')
                                @break
                            @case('rating-organization')
                                @include('rating::backend.rating_organization.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
