@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.news') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.news.manager') }}">{{trans('backend.post_manager')}}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    @if(isset($errors))

        @foreach($errors as $error)
            <div class="alert alert-danger">{!! $error !!}</div>
        @endforeach

    @endif
        <div class="clear"></div>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
                @if($model->id && userCan(['news-list-create', 'news-list-edit']))
                    <li class="nav-item"><a href="#object" class="nav-link" data-toggle="tab">{{trans("backend.object")}}</a></li>
                @endif
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    @include('news::backend.news.form.info')
                </div>
                @if($model->id)
                    <div id="object" class="tab-pane">
                        @include('news::backend.news.form.object')
                    </div>
                @endif
            </div>
        </div>
</div>
@stop
