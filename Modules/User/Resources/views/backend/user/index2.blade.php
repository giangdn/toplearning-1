@extends('layouts.backend')

@section('page_title', 'Nhân viên')

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
                        @can('user')
                            <a class="nav-item nav-link @if ($tabs == 'user')
                                active
                                @endif" id="nav-user-tab" href="{{ route('module.backend.user') }}" >{{ trans('backend.user') }}
                            </a>    
                        @endcan

                        @can('user-take-leave')
                            <a class="nav-item nav-link @if ($tabs == 'user-take-leave')
                                active
                                @endif" id="nav-user-take-leave-tab" href="{{ route('module.backend.user_take_leave') }}" >{{ trans('lacore.user_take_leave') }}
                            </a>    
                        @endcan
                        
                        <a class="nav-item nav-link @if ($tabs == 'user-contact')
                            active
                            @endif" id="nav-user-contact-tab" href="{{ route('backend.user-contact') }}">{{ trans('lamanager.user_contact') }}
                        </a>
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
                            @case('user')
                                @include('user::backend.user.index')
                                @break
                            @case('user-take-leave')
                                @include('user::backend.user_take_leave.index')
                                @break
                            @case('user-contact')
                                @include('backend.user_contact.index')
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
@endsection
