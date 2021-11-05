@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.setting') }}">{{ trans('backend.setting') }} </a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.slider') }}">{{ trans('backend.banner_manager') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#web" class="nav-link active" role="tab" data-toggle="tab">Web</a></li>
                <li class="nav-item"><a href="#mobile" class="nav-link" role="tab" data-toggle="tab">Mobile</a></li>
            </ul>
            <div class="tab-content">
                <div id="web" class="tab-pane active">
                    @include('backend.slider.form.web')
                </div>
                <div id="mobile" class="tab-pane">
                    @include('backend.slider.form.mobile')
                </div>
            </div>
        </div>
    </div>
@stop
