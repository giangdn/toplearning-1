@extends('layouts.backend')

@section('page_title', $page_title)
@section('header')
    <link rel="stylesheet" href="{{ asset('css/tree-folder.css') }}">
@endsection
@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.topic_situations') }}">Xử lý tình huống</a> <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')
<div role="main">
    <div class="clear"></div>
    <br>
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{trans('backend.info')}}</a></li>
            {{-- @if($model->id)
                <li class="nav-item"><a href="#stiuations" class="nav-link" data-toggle="tab">Tình huống</a></li>
            @endif --}}
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                @include('topicsituations::backend.topic.form.info')
            </div>
            {{-- @if($model->id)
                <div id="stiuations" class="tab-pane">
                    @include('topicsituations::backend.form.situations')
                </div>
            @endif --}}
        </div>
    </div>
</div>
@stop
