@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.speed_text') }}">Quản lý chạy tiêu đề</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <form method="post" action="{{ route('backend.speed_text.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                        <a href="{{ route('backend.speed_text') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="">{{trans('backend.titles')}}</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="title" class="form-control" value="{{ $model->title }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="status">{{trans('backend.status')}} <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-6">
                                <select name="status" id="status" class="form-control select2" data-placeholder="-- {{trans('backend.status')}} --" required>
                                    <option value="1" {{ $model->status == 1 ? 'selected' : '' }}>{{trans("backend.enable")}}</option>
                                    <option value="0" {{ (!is_null($model->status) && $model->status == 0) ? 'selected' : '' }}>{{trans("backend.disable")}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
