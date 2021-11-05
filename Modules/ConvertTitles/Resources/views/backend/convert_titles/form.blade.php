@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.convert_titles') }}">{{trans('backend.convert_titles')}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">
    <form method="post" action="{{ route('module.convert_titles.save') }}" class="form-validate form-ajax" role="form"
          enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp; {{ trans('backend.save') }}</button>
                    <a href="{{ route('module.convert_titles') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <select name="user_id" id="user_id" class="form-control select2 load-user"
                                            data-placeholder="-- Chọn nhân sự --" required>
                                        @if($model->user_id)
                                            <option value="{{ $profile->user_id }}" {{ $model->user_id == $profile->user_id ?
                                            'selected' : '' }}> {{ $profile->lastname .' '. $profile->firstname }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="probation">{{trans('backend.convert_titles')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="title_id" id="title_id" class="form-control select2 load-title"
                                            data-placeholder="-- {{trans('backend.choose_convert_titles')}} --" required>
                                        @if($model->title_id)
                                            <option value="{{ $title->id }}" {{ $model->title_id == $title->id  ? 'selected' :
                                            '' }}> {{ $title->name }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="unit_id">{{ trans('backend.training_unit') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="unit_id" id="unit_id" class="form-control select2 load-unit"
                                            data-placeholder="-- {{ trans('backend.choose_training_unit') }} --" required>
                                        @if($model->unit_id)
                                            <option value="{{ $unit->id }}" {{ $model->unit_id == $unit->id  ? 'selected' : ''
                                            }}> {{ $unit->code . ' - ' . $unit->name }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="unit_receive_id">{{ trans('backend.receivers') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="unit_receive_id" id="unit_receive_id" class="form-control select2 load-unit"
                                            data-placeholder="-- {{ trans('backend.choose_receivers') }} --" required>
                                        @if($model->unit_receive_id)
                                            <option value="{{ $unit_receive->id }}" {{ $model->unit_receive_id ==
                                            $unit_receive->id  ? 'selected' : '' }}> {{ $unit_receive->code . ' - ' .
                                            $unit_receive->name }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{trans('backend.time')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <span>
                                        <input name="start_date" type="text" class="datepicker form-control d-inline-block w-25"
                                                 placeholder="{{trans('backend.choose_start_date')}}" autocomplete="off" value="{{ get_date
                                                 ($model->start_date) }}" required>
                                    </span>
                                    <span class="fa fa-arrow-right" style="padding: 0 10px;"></span>
                                    <span>
                                        <input name="end_date" type="text" class="datepicker form-control d-inline-block w-25"
                                                 placeholder='{{trans("backend.choose_end_date")}}' autocomplete="off" value="{{ get_date
                                                 ($model->end_date) }}" required>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Thời gian gửi đánh giá </label>
                                </div>
                                <div class="col-md-6">
                                    <input name="send_date" type="text" placeholder='{{trans("backend.choose_date_send_evaluate")}}' class="datepicker
                                    form-control" autocomplete="off" value="{{ get_date($model->send_date) }}" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.note') }} </label>
                                </div>
                                <div class="col-md-6">
                                    <textarea name="note" type="text" rows="5" class="form-control" value="">{{ $model->note
                                    }}</textarea>
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
