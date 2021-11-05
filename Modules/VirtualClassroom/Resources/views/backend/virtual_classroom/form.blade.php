@extends('layouts.backend')

@section('page_title', $page_title)

@section('header')
    <script src="{{asset('styles/vendor/jqueryplugin/printThis.js')}}"></script>
    <style>
        table tbody th {
            font-weight: normal !important;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.virtualclassroom.index') }}"> {{ trans('backend.virtual_classroom') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')
    @php
        $tabs = request()->get('tabs', null);
    @endphp

<div role="main">
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            @if($model->id)
                <li class="nav-item"><a href="#teacher" class="nav-link" data-toggle="tab">{{ trans('backend.teacher') }}</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active @endif">
                @include('virtualclassroom::backend.virtual_classroom.form.info')
            </div>
            @if($model->id)
                <div id="teacher" class="tab-pane">
                    @include('virtualclassroom::backend.virtual_classroom.form.teacher')
                </div>
            @endif
        </div>
    </div>
</div>
    <script type="text/javascript">
        $('.timepicker').timepicker({
            showMeridian: false
        });
    </script>
@stop
