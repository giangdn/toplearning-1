@extends('layouts.backend')

@section('page_title', $page_title)
@section('header')
    <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
@endsection
@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.survey.index') }}">{{trans('backend.survey')}}</a> <i class="uil uil-angle-right"></i>
        <span class="">{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    @if(isset($errors))

        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach

    @endif
    <div class="clear"></div>
    <br>
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{trans('backend.info')}}</a></li>
            @if($model->id)
                <li class="nav-item"><a href="#object" class="nav-link" data-toggle="tab">{{trans('backend.object')}}</a></li>
                <li class="nav-item"><a href="#promotion" class="nav-link" data-toggle="tab">{{ trans('backend.reward_points') }}</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                @include('survey::backend.survey.form.info')
            </div>
            @if($model->id)
                <div id="object" class="tab-pane">
                    @include('survey::backend.survey.form.object')
                </div>
                <div id="promotion" class="tab-pane">
                    @include('survey::backend.survey.form.promotion')
                </div>
            @endif
        </div>
    </div>
</div>
@stop
