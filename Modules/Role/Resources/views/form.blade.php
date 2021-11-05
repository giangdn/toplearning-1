@extends('layouts.backend')

@section('page_title', $action)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    @php
        $tabs = request()->get('tabs', null);
    @endphp
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.permission') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('backend.roles') }}"><span tabindex="0">{{ trans('backend.role_management') }}</span></a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $action }}</span>
    </h2>
</div>
<div role="main" id="rolepermission">
    <div class="tPanel">
        <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
            <li class="nav-item"><a href="#base" class="nav-link  @if($tabs == 'base' || empty($tabs)) active @endif" role="tab" data-toggle="tab">{{ trans('backend.role') }}</a></li>
            @if(isset($role->id))
            <li class="nav-item"><a href="#permission1" class="nav-link @if($tabs == 'permission') active @endif" data-toggle="tab">{{ trans('backend.permission') }}</a></li>
            @endif
        </ul>
        <div class="tab-content">
            <div id="base" class="tab-pane active">
                @include('role::base')
            </div>
            @if(isset($role->id))
            <div id="permission1" class="tab-pane">
                @include('role::permission')
            </div>
            @endif
        </div>
    </div>
</div>
<script src="{{ asset('styles/module/role/js/role.js?v=1') }}"></script>
@stop
