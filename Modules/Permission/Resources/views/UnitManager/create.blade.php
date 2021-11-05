@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.permission') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.permission.unitmanager') }}">{{ trans('backend.unit_manager_setup') }}</a>
            <i class="uil uil-angle-right"></i>
            <span>Thêm mới</span>
        </h2>
    </div>
    <div role="main">
        <form method="post" action="{{ route('backend.permission.unitmanager.save') }}" class="form-validate form-ajax " role="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.unit') }} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <select name="unit_id" class="form-control load-unit" data-placeholder="{{trans('backend.choose_unit')}}"></select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 1 <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority1[]" id="priority1" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 2</label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority2[]" id="priority2" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 3</label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority3[]" id="priority3" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 4</label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority4[]" id="priority4" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{route('backend.permission.unitmanager')}}" class="btn btn-warning">Trở về</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
