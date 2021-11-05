@extends('layouts.backend')

@section('page_title', 'Thí sinh bên ngoài')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.quiz') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.quiz.manager') }}">{{ trans('backend.quiz_list') }}</a>
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.quiz.edit', ['id' => $quiz_id]) }}">{{ $quiz_name->name }}</a>
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.quiz.register.user_secondary', ['id' => $quiz_id]) }}">{{ trans('backend.user_secondary') }}</a>
        <i class="uil uil-angle-right"></i>
        <span>{{trans('backend.add_new')}}</span>
    </h2>
</div>
<div role="main">
    <form method="post" action="{{ route('module.quiz.register.user_secondary.save_new_user', ['id' => $quiz_id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['quiz-user-secondary-create', 'quiz-user-secondary-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false">
                        <i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}
                    </button>
                    @endcanany
                    <a href="{{ route('module.quiz.register.user_secondary', ['id' => $quiz_id]) }}" class="btn btn-warning">
                        <i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}
                    </a>
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
                                    <label>Ca thi <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="part_id" class="form-control select2" data-placeholder="-- {{trans('backend.exams')}} --" required>
                                        <option value=""></option>
                                        @foreach ($quiz_part as $part)
                                            <option value="{{ $part->id }}" >{{ $part->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{trans('backend.employee_outside_code')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="code" type="text" class="form-control" value="" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.fullname') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="name" type="text" class="form-control" value="" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{trans('backend.user_name')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input name="username" type="text" class="form-control" value="" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{trans('backend.pass')}} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input name="password" id="password" type="password" class="form-control" value="" placeholder="{{trans('backend.pass')}}" autocomplete="off">
                                        </div>
                                        <div class="col-md-6">
                                            <input name="repassword" id="repassword" type="password" class="form-control" value="" placeholder="{{trans('backend.repassword')}}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.dob') }}</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="dob" type="text" class="form-control datepicker" value="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>Email</label>
                                </div>
                                <div class="col-md-6">
                                    <input name="email" type="text" class="form-control" value="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.identity_card') }} <span class="text-danger">*</span> </label>
                                </div>
                                <div class="col-md-6">
                                    <input name="identity_card" type="text" class="form-control is-number" value="" required>
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
