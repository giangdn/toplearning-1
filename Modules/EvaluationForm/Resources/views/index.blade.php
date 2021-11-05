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
        @can('rating-template')
        <div class="col-md-2">
            <div class="category-icon">
                <a href="{{ route('module.rating.template') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.rating.template') }}">{{ trans('backend.assessment_after_the_course') }}</a>
            </div>
        </div>
        @endcan

        {{-- @can('plan-app-template')
        <div class="col-md-2">
            <div class="category-icon">
                <a href="{{ route('module.plan_app.template') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.plan_app.template') }}">{{ trans('backend.evaluate_training_effectiveness') }}</a>
            </div>
        </div>
        @endcan --}}

       {{-- @can('convert-titles-review')
        <div class="col-md-2">
            <div class="category-icon">
                <a href="{{ route('module.convert_titles.reviews') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.convert_titles.reviews') }}">{{trans('backend.convert_titles_rate')}}</a>
            </div>
        </div>
        @endcan--}}
    </div>
@endsection
