@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.category') }}">@lang('backend.categories')</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.training_action.field') }}">@lang('backend.field_category')</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <form method="post" action="{{ route('module.training_action.field.save') }}" class="form-validate form-ajax " role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;@lang('backend.save')</button>
                        <a href="{{ route('module.training_action.field') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> @lang('backend.cancel')</a>
                    </div>
                </div>
            </div>

            <div class="clear"></div>

            <br>
            <div class="tPanel">
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">@lang('backend.info')</a></li>
                </ul>
                <div class="tab-content">
                    <div id="base" class="tab-pane active">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>@lang('backend.code') <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>@lang('backend.name') <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>@lang('backend.name_en')</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="name_en" type="text" class="form-control" value="{{ $model->name_en }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>@lang('backend.status') <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="radio-inline"><input type="radio" required name="status" value="1" @if($model->status == 1) checked @endif>@lang('backend.enable')</label>
                                        <label class="radio-inline"><input type="radio" required name="status" value="0" @if($model->status == 0) checked @endif>@lang('backend.disable')</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
