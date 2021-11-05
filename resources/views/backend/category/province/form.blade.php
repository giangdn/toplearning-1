@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.category.province') }}">{{ trans('backend.province') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

<div role="main">

    <form method="post" action="{{ route('backend.category.province.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
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
                                    <label>{{ trans('backend.code') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" {{$model->id?'disabled':''}} min="1" class="form-control" name="id" value="{{$model->id}}">
                                    @if($model->id)
                                        <input type="hidden" name="id" value="{{ $model->id }}">
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.province_name') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{$model->name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                </div>
                                <div class="col-md-6">
                                    @canany(['category-province-create', 'category-province-edit'])
                                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                                    @endcanany
                                    <a href="{{ route('backend.category.province') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i>{{ trans('backend.back') }}</a>
                                    <input type="hidden" name="action" value="{{$model->id?0:1}}">
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
