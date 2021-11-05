@extends('layouts.backend')

@section('page_title', 'Danh sách chương trình khung')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{trans('backend.list_roadmap')}}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div class="row">

        @can('training-roadmap')
        <div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('module.trainingroadmap') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.trainingroadmap') }}">{{trans('backend.trainingroadmap')}}</a>
            </div>
        </div>
        @endcan

        {{--@can('potential-roadmap')
        <div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('module.potential.roadmap.list_title') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.potential.roadmap.list_title') }}">Chương trình khung nhân sự tiềm năng</a>
            </div>
        </div>
        @endcan--}}

        {{--<div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('module.new_recruitment.roadmap.list_title') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.new_recruitment.roadmap.list_title') }}">Chương trình khung nhân sự tân tuyển</a>
            </div>
        </div>--}}

        {{--@can('convert-titles-roadmap')
        <div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('module.convert_titles.roadmap.list_title') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.convert_titles.roadmap.list_title') }}">{{trans('backend.title_conversion_program')}}</a>
            </div>
        </div>
        @endcan--}}
    </div>
@endsection
