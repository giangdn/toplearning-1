@extends('layouts.backend')

@section('page_title', 'Dữ liệu cũ')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.quiz.data_old_quiz') }}">Dữ liệu cũ</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $model->user_name }}</span>
        </h2>
    </div>
@endsection

@section('content')
<div role="main">
    <form method="post" action="{{ route('module.quiz.save_edit_data_old') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp; {{ trans('backend.save') }}</button>
                    <a href="{{ route('module.quiz.data_old_quiz') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                </div>
            </div>
        </div>
        <div class="tPanel">
            <ul class="nav nav-pills ml-3 mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12 m-3">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Mã Nhân viên</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="user_code" type="text" class="form-control" value="{{ $model->user_code }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Tên Nhân viên</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="user_name" type="text" class="form-control" value="{{ $model->user_name }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label> Chức danh</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="title" type="text" class="form-control" value="{{ $model->title }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Trực thuộc</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="area" type="text" class="form-control" value="{{ $model->area }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Đơn vị</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="unit" type="text" class="form-control" value="{{ $model->unit }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Phòng/Ban/TT</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="department" type="text" class="form-control" value="{{ $model->department }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Email</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="email" type="text" class="form-control" value="{{ $model->email }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Mã kỳ thi</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="quiz_code" type="text" class="form-control" value="{{ $model->quiz_code }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Tên kỳ thi</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="quiz_name" type="text" class="form-control" value="{{ $model->quiz_name }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Thời gian bắt đầu</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="start_date" type="text" class="datepicker form-control d-inline-block" placeholder="{{trans('backend.choose_start_date')}}"  value="{{ get_date($model->start_date) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Thời gian kết thúc</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="end_date" type="text" class="datepicker form-control d-inline-block" placeholder="{{trans('backend.choose_end_date')}}"  value="{{ get_date($model->end_date) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Điểm thi trắc nghiệm</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="score_essay" type="text" class="form-control" value="{{ $model->score_essay }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Điểm thi</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="score_multiple_choice" type="text" class="form-control" value="{{ $model->score_multiple_choice }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Kết quả</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="result" type="text" class="form-control" value="{{ $model->result }}">
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
