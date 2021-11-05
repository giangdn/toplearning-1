@extends('layouts.backend')

@section('page_title', 'Chỉnh sửa')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.study_promotion_program') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.promotion') }}">{{ trans('backend.study_promotion_program') }}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{trans('backend.edit')}}</span>
        </h2>
    </div>
    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <form method="post" action="{{ route('module.promotion.update',$promotion->id) }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.gift_code') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="code" type="text" class="form-control" value="{{ $promotion->code }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.gift_name') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="name" type="text" class="form-control" value="{{ $promotion->name }}" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.promotion_category') }}</label><span class="text-danger"> * </span>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="promotion_group" id="promotion_group" class="form-control select2" data-placeholder="{{ trans('backend.promotion_category') }}">
                                            <option value=""></option>
                                            @if($promotion_groups)
                                                @foreach($promotion_groups as $promotion_group)
                                                    <option value="{{ $promotion_group->id }}" @if($promotion->promotion_group == $promotion_group->id) selected @endif >{{ $promotion_group->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.quantity') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="amount" type="number" class="form-control" value="{{ $promotion->amount }}" min="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.points_change') }}<span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="point" type="number" class="form-control" value="{{ $promotion->point }}"  min="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.duration') }}</label><span class="text-danger"> * </span>
                                    </div>
                                    <div class="col-md-9">
                                        <span><input name="period" type="text" placeholder='{{trans("backend.choose_end_date")}}' class="form-control d-inline-block w-25" autocomplete="off" value="{{ \Carbon\Carbon::parse($promotion->period)->format('d/m/Y H:i') }}" id="datetimepicker"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.contacts') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input name="contact" type="text" class="form-control" value="{{ $promotion->contact }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{ trans('backend.rules') }}</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea name="rules" id="rules" class="form-control">{{ $promotion->rules }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="row row-acts-btn">
                                    <div class="col-sm-12">
                                        <div class="btn-group act-btns">
                                            @can('promotion-edit')
                                            <button type="submit" class="btn btn-primary @if(\App\Permission::isUnitManager()) hidden @endif" data-must-checked="false"><i class="fa fa-save"></i> {{ trans('backend.save') }}</button>
                                            @endcan
                                            <a href="{{ route('module.promotion') }}" class="btn btn-warning @if(\App\Permission::isUnitManager()) hidden @endif"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h4>{{trans('backend.picture')}} (290 x 290)</h4>
                                    <div>
                                        <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                                        <div id="image-review">
                                            <img src="{{ image_file($promotion->images) }}" alt="">
                                        </div>
                                        <input name="images" id="image-select" type="text" class="d-none" value="{{ $promotion->images }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3 control-label">
                                        <label>{{trans('backend.status')}}</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="radio-inline"><input type="radio" name="status" @if($promotion->status == 1) checked @endif value="1">{{trans("backend.enable")}}</label>
                                        <label class="radio-inline"><input type="radio" name="status" @if($promotion->status == 0) checked @endif value="0">{{trans("backend.disable")}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            CKEDITOR.replace('rules', {
                                filebrowserImageBrowseUrl: '/filemanager?type=image',
                                filebrowserBrowseUrl: '/filemanager?type=file',
                                filebrowserUploadUrl : null, //disable upload tab
                                filebrowserImageUploadUrl : null, //disable upload tab
                                filebrowserFlashUploadUrl : null, //disable upload tab
                            });
                        </script>
                        <script>
                            $('#datetimepicker').datetimepicker({
                                locale:'vi',
                                format: 'DD/MM/YYYY HH:mm',
                                // minDate:new Date(),
                                // disabledDates: [new Date()]
                            });
                            $("#select-image").on('click', function () {
                                var lfm = function (options, cb) {
                                    var route_prefix = '/filemanager';
                                    window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                                    window.SetUrl = cb;
                                };

                                lfm({type: 'image'}, function (url, path) {
                                    $("#image-review").html('<img src="'+ path +'">');
                                    $("#image-select").val(path);
                                });
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
