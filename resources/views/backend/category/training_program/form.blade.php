@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.category.training_program') }}">{{ @trans('backend.topic_training_program') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">

    <form method="post" action="{{ route('backend.category.training_program.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-training-program-create', 'category-training-program-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('backend.category.training_program') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label>{{ trans('backend.topic_training_program_code') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="code" type="text" class="form-control" value="{{ $model->code }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.topic_training_program') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.status') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <label class="radio-inline"><input type="radio" required name="status" value="1" @if($model->status == 1) checked @endif>{{ trans('backend.enable') }}</label>
                                    <label class="radio-inline"><input type="radio" required name="status" value="0" @if($model->status == 0) checked @endif>{{ trans('backend.disable') }}</label>
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
