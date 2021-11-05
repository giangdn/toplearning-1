@extends('layouts.backend')

@section('page_title', trans('backend.training_program_learned'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.backend.user') }}">{{ trans('backend.user_management') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.backend.user.edit',['id' => $user_id]) }}">{{ $full_name }}</a>
            <i class="uil uil-angle-right"></i>
            <span class=""> {{ trans('backend.training_program_learned') }}</span>
        </h2>
    </div>
    @if($user_id)
        @include('user::backend.layout.menu')
    @endif
    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active">{{ trans('backend.info') }}</li>
            </ul>
            <div class="tab-content">
                <form method="post" action="{{ route('module.backend.training_program_learned.save', ['user_id' => $user_id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="{{ $user_id }}">
                    <input type="hidden" name="id" value="{{ $model->id }}">
                    <div class="form-group row m-2">
                        <div class="col-md-10">
                            <div class="form-group row">
                                <div class="col-md-2 control-label"> {{ trans('backend.training_program') }}</div>
                                <div class="col-md-8">
                                    <input type="text" name="training_program" class="form-control" value="{{ $model->training_program }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 control-label">
                                    {{ trans('backend.time') }}
                                </div>
                                <div class="col-md-8">
                                    <input name="time" type="text" class="form-control" autocomplete="off" value="{{ $model->time }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 control-label"> {{ trans('backend.note') }} </div>
                                <div class="col-md-8">
                                    <textarea name="note" id="note" rows="5" class="form-control">{{ $model->note }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 text-right">
                            <div class="btn-group act-btns">
                                <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                                <a href="{{ route('module.backend.training_program_learned', ['user_id' => $user_id]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
