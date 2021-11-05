@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.potential.index') }}">Nhân sự tiềm năng</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">
        <form method="post" action="{{ route('module.potential.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @canany(['potential-create', 'potential-edit'])
                            <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                        @endcanany
                        <a href="{{ route('module.potential.index') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                        <label for="user_id">{{trans("backend.user")}} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="user_id" id="user_id" class="form-control select2 load-user" data-placeholder="-- Chọn nhân sự--" required>
                                            @if($model->user_id)
                                                <option value="{{ $profile->user_id }}" {{ $model->user_id == $profile->user_id ? 'selected' : '' }}> {{ $profile->lastname .' '. $profile->firstname }} </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
{{--                                <div class="form-group row">--}}
{{--                                    <div class="col-sm-3 control-label">--}}
{{--                                        <label for="ratio">Tỷ lệ đánh giá</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <input type="text" class="form-control is-number" name="ratio" value="{{ $model->ratio }}" placeholder="Nhập %">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group row">--}}
{{--                                    <div class="col-sm-3 control-label">--}}
{{--                                        <label for="group_percent">Thuộc nhóm</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <input type="text" class="form-control" name="group_percent" value="{{ $model->group_percent }}" placeholder="Nhập tên nhóm">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group row">--}}
{{--                                    <div class="col-sm-3 control-label">--}}
{{--                                        <label for="d1">Quý 1</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <input type="text" class="form-control" name="d1" value="{{ $model->d1 }}" placeholder="Quý 1">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group row">--}}
{{--                                    <div class="col-sm-3 control-label">--}}
{{--                                        <label for="d2">Quý 2</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <input type="text" class="form-control" name="d2" value="{{ $model->d2 }}" placeholder="Quý 2">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-group row">--}}
{{--                                    <div class="col-sm-3 control-label">--}}
{{--                                        <label for="d3">Quý 3</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-6">--}}
{{--                                        <input type="text" class="form-control" name="d3" value="{{ $model->d3 }}" placeholder="Quý 3">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{trans('backend.time')}} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <span><input name="start_date" type="text" class="datepicker form-control
                                        d-inline-block w-25" placeholder="{{trans('backend.choose_start_date')}}" autocomplete="off" value="{{ get_date
                                        ($model->start_date) }}" required></span>
                                        <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                        <span><input name="end_date" type="text" class="datepicker form-control d-inline-block
                                        w-25" placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off" value="{{ get_date
                                        ($model->end_date) }}" required></span>
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
