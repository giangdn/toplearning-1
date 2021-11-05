@extends('layouts.backend')

@section('page_title', 'Mẫu đánh giá')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ trans('backend.evaluation_form') }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-2">
            <div class="scb-category-icon">
                <a href="{{ route('module.rating.template') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.rating.template') }}">{{ trans('backend.assessment_after_the_course') }}</a>
            </div>
        </div>

    </div>

@endsection
