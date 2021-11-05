@extends('layouts.backend')

@section('page_title', 'Chỉnh sửa')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.study_promotion_program') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.promotion.level') }}">{{trans('backend.armorial')}}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{trans('backend.edit')}}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <form method="post" action="{{ route('module.promotion.level.update',$level->id) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @can('promotion-level-edit')
                            <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                        @endcan
                        <a href="{{ route('module.promotion.level') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <br>
            <div class="tPanel">
                <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                    <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="base" class="tab-pane active">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label>{{ trans('backend.badge_code') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="code" type="text" class="form-control" value="{{ $level->code }}" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label>{{ trans('backend.badge_name') }} <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="name" type="text" class="form-control" value="{{ $level->name }}" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label>{{ trans('backend.rank') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="level" type="number" class="form-control" value="{{ $level->level }}"  min="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label>{{ trans('backend.points_achieved') }}<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <input name="point" type="number" class="form-control" value="{{ $level->point }}"  min="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label>{{trans('backend.picture')}} (290 x 290)<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-md-9">
                                            <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                                            <div id="image-review" style="border: dashed 1px;height: 400px;width: 400px;">
                                                <img src="{{ $level->images }}" alt="">
                                            </div>
                                            <input name="images" id="image-select" type="text" class="d-none" value="{{ $level->images }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label">
                                            <label>{{trans('backend.status')}}</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="radio-inline"><input type="radio" name="status" @if($level->status == 1) checked @endif value="1">{{trans("backend.enable")}}</label>
                                            <label class="radio-inline"><input type="radio" name="status" @if($level->status == 0 ) checked @endif value="0">{{trans("backend.disable")}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script src="{{ asset('styles/ckeditor/ckeditor.js') }}"></script>
                            <script>
                                $("#select-image").on('click', function () {
                                    var lfm = function (options, cb) {
                                        var route_prefix = '/filemanager';
                                        window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                                        window.SetUrl = cb;
                                    };

                                    lfm({type: 'image'}, function (url, path) {
                                        $("#image-review").html('<img src="'+ path +'" width="400px" height="400px">');
                                        $("#image-select").val(path);
                                    });
                                });
                            </script>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
