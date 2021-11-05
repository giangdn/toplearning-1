@extends('layouts.backend')

@section('page_title', 'Tạo mới')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.study_promotion_program') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.promotion.group') }}">{{ trans('backend.promotion_category_group') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{trans('backend.create')}}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <form method="post" action="{{ route('module.promotion.group.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @can('promotion-group-create')
                            <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                        @endcan
                        <a href="{{ route('module.promotion.group') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                        <label for="icon">Icon (300x300) <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <div>
                                            <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                                            <div id="image-review"></div>
                                            <input name="icon" id="image-select" type="text" class="d-none" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label for="code">{{trans('backend.promotion_category_group_code')}} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="code" type="text" class="form-control"  value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label for="name">{{trans('backend.promotion_category_group_name')}} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <input name="name" type="text" class="form-control"  value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{trans('backend.status')}}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="radio-inline"><input type="radio" name="status" checked value="1">{{trans("backend.enable")}}</label>
                                        <label class="radio-inline"><input type="radio" name="status" value="0">{{trans("backend.disable")}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $("#select-image").on('click', function () {
                    var lfm = function (options, cb) {
                        var route_prefix = '/filemanager';
                        window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                        window.SetUrl = cb;
                    };

                    lfm({type: 'image'}, function (url, path) {
                        $("#image-review").html('<img src="'+ path +'" class="w-50">');
                        $("#image-select").val(path);
                    });
                });
            </script>
        </form>
    </div>
@stop
