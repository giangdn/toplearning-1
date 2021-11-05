@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.category') }}">{{ trans('backend.category') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.category.district') }}">{{ trans('backend.district') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <form method="post" action="{{ route('backend.category.district.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
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
                                        <label>{{ trans('backend.province') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="province_id" class="form-control select2">
                                            <option value="">-- {{ trans('backend.province') }} --</option>
                                            @foreach($province as $item)
                                                <option @if($item->id==$model->province_id) selected @endif value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.district_code') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" {{$model->id?'disabled':''}} name="id" value="{{$model->id}}" class="form-control">
                                        @if($model->id)
                                            <input type="hidden" name="id" value="{{ $model->id }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.district') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="name" value="{{$model->name}}" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                    </div>
                                    <div class="col-md-6">
                                        @canany(['category-district-create', 'category-district-edit'])
                                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                                        @endcanany
                                        <a href="{{ route('backend.category.district') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i>{{ trans('backend.back') }}</a>
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
