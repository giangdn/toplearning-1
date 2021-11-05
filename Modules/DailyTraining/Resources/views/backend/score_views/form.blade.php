@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training_video') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.daily_training.score_views') }}">{{ trans('backend.setting_views') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">
    <form method="post" action="{{ route('module.daily_training.score_views.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['score-view-create','score-view-edit'])
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp; {{ trans('backend.save') }}</button>
                    @endcan
                    <a href="{{ route('module.daily_training.score_views') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.views_from') }}</label>
                                </div>
                                <div class="col-md-3">
                                    <input name="from" type="text" placeholder="{{ trans('backend.enter_quantity') }}" min="1" class="form-control is-number" value="{{ $model->from }}" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.views_to') }}</label>
                                </div>
                                <div class="col-md-3">
                                    <input name="to" type="text" placeholder="{{ trans('backend.enter_quantity') }}" class="form-control is-number" value="{{ $model->to }}" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.score') }}</label>
                                </div>
                                <div class="col-md-3">
                                    <input name="score" type="text" placeholder="{{ trans('backend.enter_score') }}" class="form-control is-number" value="{{ $model->score }}" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
