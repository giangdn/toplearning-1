@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
@php
    $tabs = request()->get('tabs', null);
    $get_type_model_costs = json_decode($model->type_costs);
@endphp
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.training_plan') }}">{{trans('backend.training_plan')}}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    <form method="post" action="{{ route('module.training_plan.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data" id="form">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['training-plan-create', 'training-plan-edit'])
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.training_plan') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item">
                    <a href="#base" class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" id="base_tab" role="tab" data-toggle="tab">
                        {{ trans('backend.info') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($tabs == 'cost') active @endif" href="#cost" id="cost_tab" role="tab" data-toggle="tab">Định mức chi phí/lớp</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active  @endif">
                    @include('trainingplan::backend.plan.info')
                </div>
                <div id="cost" class="tab-pane @if($tabs == 'cost') active  @endif">
                    @include('trainingplan::backend.plan.cost')
                </div>
            </div>
        </div>
    </form>

</div>

@stop
