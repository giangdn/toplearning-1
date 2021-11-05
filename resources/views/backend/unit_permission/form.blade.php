@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('backend.permission') }}">Phân quyền</a> / <a href="{{ route('backend.unit_permission') }}">{{ trans('backend.unit_group') }}</a> / {{ $page_title }}
        </h2>
    </div>
@endsection

@section('content')

<div role="main">

    <form method="post" action="{{ route('backend.unit_permission.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>

                    <a href="{{ route('backend.unit_permission') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                    <label>{{ trans('backend.unit') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="unit_id" id="unit_id" class="form-control load-unit" data-placeholder="-- {{ trans('backend.choose_unit') }} --">
                                        @if(isset($unit))
                                            <option value="{{ $unit->id }}" {{ $model->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->code . ' - ' . $unit->name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.manager') }}<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="user_id[]" id="user_id[]" class="form-control load-user" multiple data-placeholder="-- {{ trans('backend.choose_manager') }} --">
                                        @if(isset($user))
                                            <option value="{{ $user->user_id }}" {{ $model->user_id == $user->user_id ? 'selected' : '' }}>{{ $user->code . ' - ' . $user->lastname . ' ' . $user->firstname }}</option>
                                        @endif
                                    </select>
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
