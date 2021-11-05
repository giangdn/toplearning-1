@extends('layouts.backend')

@section('page_title', 'Đào tạo đơn vị')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{ trans('backend.training_unit') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div class="row">
        @can('training-unit-offline-course')
        <div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('module.training_unit.offline') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.training_unit.offline') }}"> {{ trans('backend.offline_course') }}</a>
            </div>
        </div>
        @endcan

        @can('training-unit-quiz')
        <div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('module.training_unit.quiz') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.training_unit.quiz') }}"> {{ trans('backend.quiz_list') }}</a>
            </div>
        </div>
        @endcan

        @if(\App\Permission::isUnitManager() || \App\Permission::isAdmin())
            <div class="col-md-2">
                <div class="scb-category-icon">
                    <a href="{{ route('module.training_unit.approve_course') }}"><span class="fa fa-building"></span></a>
                    <a href="{{ route('module.training_unit.approve_course') }}"> Duyệt ghi danh</a>
                </div>
            </div>
        @endif
    </div>
@endsection
