@extends('layouts.backend')

@section('page_title', trans('backend.category'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">{{ trans('backend.category') }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h2>{{ trans('backend.training_action') }}</h2>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-2">
            <div class="category-icon">
                <a href="{{ route('module.training_action.category') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.training_action.category') }}">{{ trans('backend.training_action_category') }}</a>
            </div>
        </div>

        <div class="col-md-2">
            <div class="category-icon">
                <a href="{{ route('module.training_action.person_charge') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.training_action.person_charge') }}">{{ trans('backend.person_charge') }}</a>
            </div>
        </div>

        <div class="col-md-2">
            <div class="category-icon">
                <a href="{{ route('module.training_action.field') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.training_action.field') }}">{{ trans('backend.field') }}</a>
            </div>
        </div>

        <div class="col-md-2">
            <div class="category-icon">
                <a href="{{ route('module.training_action.role') }}"><span class="fa fa-building"></span></a>
                <a href="{{ route('module.training_action.role') }}">{{ trans('backend.role') }}</a>
            </div>
        </div>
    </div>

@endsection
