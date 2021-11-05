@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.notify_send') }}">{{ trans('backend.notify') }}</a> <i class="uil uil-angle-right"></i>
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
            <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            @if($model->id && userCan(['notify-create', 'notify-edit']))
                <li class="nav-item"><a href="#object" class="nav-link" data-toggle="tab">{{ trans('backend.object') }}</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                @include('notify::backend.notify_send.form.info')
            </div>
            @if($model->id)
                <div id="object" class="tab-pane">
                    @include('notify::backend.notify_send.form.object')
                </div>
            @endif
        </div>
    </div>
</div>
@stop
