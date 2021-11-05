@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.category.training_teacher') }}">{{ trans('backend.teacher') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">

    <form method="post" action="{{ route('backend.category.training_teacher.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['category-teacher-create', 'category-teacher-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('backend.category.training_teacher') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label for="type">{{trans('backend.form')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="type" id="type" class="form-control" required data-placeholder="-- {{trans('backend.choose_form')}} --" @if(isset($id)) disabled @endif >
                                        <option value="1" {{ $model->type == 1 ? 'selected' : '' }}>{{trans("backend.internal")}}</option>
                                        <option value="2" {{ $model->type == 2 ? 'selected' : '' }}>{{trans("backend.outside")}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ((isset($model->type) && $model->type == 1) || empty($model->type))
                    <div class="row">
                        <div class="col-md-12" id="form-internal">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.choose_user') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="user_id" id="user_id" class="form-control select2 ">
                                        <option value="" disabled selected>--{{ trans('backend.choose_user') }}--</option>
                                        @if(isset($user))
                                            <option selected value="{{ $user->user_id }}">
                                                {{ $user->code . ' - ' . $user->lastname . ' ' . $user->firstname }}
                                            </option>
                                        @endif
                                        @foreach($get_users_not_regis as $user_not_regis)
                                            <option value="{{ $user_not_regis->user_id }}">
                                                {{ $user_not_regis->code . ' - ' . $user_not_regis->lastname . ' ' . $user_not_regis->firstname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.teacher_code') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="code" id="code" type="text" class="form-control" value="{{ $model->code }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.teacher_name') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" id="name" type="text" class="form-control" value="{{ $model->name }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Email <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="email" id="email" type="text" class="form-control" value="{{ $model->email }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.teacher_phone') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="phone" id="phone" type="text" class="form-control" value="{{ $model->phone }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Số tài khoản</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="account_number" id="account_number" type="text" class="form-control" value="{{ $model->account_number }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.unit') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input id="unit" type="text" class="form-control" value="{{ isset($unit) ? $unit->code . ' ' . $unit->name : ''
                                     }}" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.title') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control" value="{{ isset($title) ? $title->code . ' ' . $title->name :
                                   ''}}" disabled>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="teacher_type_id">{{ trans('backend.teacher_type') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <select name="teacher_type_id" id="teacher_type_id" class="form-control select2" data-placeholder="-- {{ trans('backend.teacher_type') }} --" >
                                        <option value=""></option>
                                        @foreach($teacher_types as $teacher_type)
                                            <option value="{{ $teacher_type->id }}" {{ $model->teacher_type_id ==  $teacher_type->id ? 'selected' : '' }}>{{ $teacher_type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if ((isset($model->type) && $model->type == 2) || empty($model->type))
                            <div class="form-group row">
                                <div class="col-md-3 control-label">
                                    <label for="training_partner_id">Đối tác </label>
                                </div>
                                <div class="col-md-6">
                                    <select name="training_partner_id" id="training_partner" class="form-control select2" data-placeholder="-- Chọn đối tác --" >
                                        <option value=""></option>
                                        @foreach($training_partner as $item)
                                            <option value="{{ $item->id }}" {{ $model->training_partner_id ==  $item->id ? 'selected' : ''}}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.status') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <label class="radio-inline"><input type="radio" required name="status" value="1" @if($model->status == 1) checked @endif>Đang làm việc</label>
                                    <label class="radio-inline"><input type="radio" required name="status" value="0" @if($model->status == 0) checked @endif>Nghỉ việc</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var ajax_get_user = "{{ route('backend.category.ajax_get_user') }}";
</script>

<script src="{{ asset('styles/module/training_teacher/js/training_teacher.js') }}"></script>
@stop
