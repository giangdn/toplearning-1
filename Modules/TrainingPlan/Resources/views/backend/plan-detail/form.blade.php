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
@endphp
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.training_plan') }}">{{trans('backend.training_plan')}}</a>
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.training_plan.detail', ['id' => $plan_id]) }}">Chi tiết kế hoạch đào tạo</a>
        <i class="uil uil-angle-right"></i>
        <span>{{ $page_title }}</span>
    </h2>
</div>
<div role="main">
    @php
        $get_type_model_costs = json_decode($model->type_costs);
    @endphp
    <form id="form" method="post" action="{{ route('module.training_plan.detail.save', ['id' => $plan_id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['training-plan-detail-create', 'training-plan-detail-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.training_plan.detail', ['id' => $plan_id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item">
                    <a class="nav-link @if($tabs == 'base' || empty($tabs)) active @endif" href="#base" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane @if($tabs == 'base' || empty($tabs)) active  @endif">
                    @include('trainingplan::backend.plan-detail.info')
                </div>
            </div>
        </div>
    </form>
</div>

@stop
