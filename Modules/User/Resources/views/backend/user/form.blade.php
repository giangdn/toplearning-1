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
        {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.backend.user') }}">{{ trans('backend.user') }}</a> <i class="uil uil-angle-right"></i>
        <span class="">{{ $page_title }}</span>
    </h2>
</div>
@if($user_id)
    @include('user::backend.layout.menu')
@endif
<div role="main">
    <form method="post" action="{{ route('module.backend.user.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    @include('user::backend.user.info')
                </div>
            </div>
        </div>
    </form>

</div>


@stop
