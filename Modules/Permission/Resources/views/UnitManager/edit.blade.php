@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.permission.unitmanager') }}">{{ trans('backend.unit_manager_setup') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Cập nhật</span>
        </h2>
    </div>
@endsection
@section('content')
    <div role="main">
        <form method="post" action="{{ route('backend.permission.unitmanager.update',$model->id) }}" class="form-validate form-ajax " role="form" enctype="multipart/form-data">
            @method('put')
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.unit') }} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <select name="unit_id" class="form-control load-unit" required data-placeholder="{{trans('backend.choose_unit')}}">
                                <option value="{{$unit->id}}" selected>{{$unit->name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 1 <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority1[]" id="priority1" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                @foreach($priority1 as $key=>$value)
                                <option value="{{$value->id}}" selected>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 2</label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority2[]" id="priority2" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                @foreach($priority2 as $key=>$value)
                                    <option value="{{$value->id}}" selected>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 3</label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority3[]" id="priority3" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                @foreach($priority3 as $key=>$value)
                                    <option value="{{$value->id}}" selected>{{$value->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label>{{ trans('backend.priority') }} 4</label>
                        </div>
                        <div class="col-md-6">
                            <select name="priority4[]" id="priority4" multiple class="form-control load-title" data-placeholder="-- {{ trans('backend.choose_title') }} --">
                                @foreach($priority4 as $key=>$value)
                                    <option value="{{$value->id}}" selected>{{$value->name}}</option>
                                @endforeach
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
