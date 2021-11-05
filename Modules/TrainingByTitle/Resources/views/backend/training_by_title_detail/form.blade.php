@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.training_by_title') }}">Lộ trình đào tạo</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.training_by_title.detail', ['id' => $training_titles->id]) }}">{{ $training_titles->title->name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">
    <form method="post" action="{{ route('module.training_by_title.detail.save',['id' => $training_titles->id] ) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['training-by-title-detail-create', 'training-by-title-detail-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.training_by_title.detail', ['id' => $training_titles->id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label for="subject_id">{{ trans('backend.subject') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="subject_id" id="subject_id" class="form-control load-subject" data-placeholder="-- {{ trans('backend.subject') }} --" required>
                                        @if($subject)
                                            <option value="{{ $subject->id }}" selected> {{ $subject->name }} </option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="date_type">{{ trans('backend.type') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="date_type" id="date_type" class="form-control select2" data-placeholder="-- {{trans('backend.type')}} --">
                                        <option value=""></option>
                                        <option value="1" {{ $model->date_type == 1 ? 'selected' : '' }}> Ngày vào làm</option>
                                        <option value="2" {{ $model->date_type == 2 ? 'selected' : '' }}> Ngày bổ nhiệm chức danh</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="from_date">{{ trans('backend.from_date') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-1">
                                    <input name="from_date" type="text" class="form-control is-number" value="{{ $model->from_date }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="to_date">{{ trans('backend.to_date') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-1">
                                    <input name="to_date" type="text" class="form-control is-number" value="{{ $model->to_date }}" required>
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
