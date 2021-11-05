@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ $type == 0 ? trans('backend.news_general') : trans('backend.news') }}
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.advertising_photo',['type' => $type]) }}">{{ $type == 0 ? 'Ảnh quảng cáo tin tức chung' : 'Ảnh quảng cáo tin tức' }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="tPanel">
            <div class="tab-content">
                <form method="post" action="{{ route('backend.advertising_photo.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{ $model->id }}">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group act-btns">
                                @can(['advertising-photo-create','advertising-photo-edit'])
                                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                                @endcan
                                <a href="{{ route('backend.advertising_photo',['type' => $type]) }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="image">{{trans('backend.picture')}} <span class="text-danger">*</span> <br>({{trans('backend.size')}}: 300x500)</label>
                        </div>

                        <div class="col-sm-6">
                            <a href="javascript:void(0)" id="select-image-web">{{trans('backend.choose_picture')}}</a>
                            <div id="image-review-web">
                                @if($model->image) <img src="{{ image_file($model->image) }}" class="w-25"> @endif
                            </div>
                            <input type="hidden" class="form-control" name="image" id="image-select-web" value="{{ $model->image }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="status">{{trans('backend.status')}} <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-6">
                            <select name="status" id="status" class="form-control select2-default" data-placeholder="-- {{trans('backend.status')}} --" required>
                                <option value="1" {{ $model->status == 1 ? 'selected' : '' }}> {{ trans("backend.enable") }}</option>
                                <option value="0" {{ (!is_null($model->status) && $model->status == 0) ? 'selected' : '' }}> {{ trans("backend.disable") }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="url">Url <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="url" class="form-control" placeholder="Nhập đường dẫn">
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#select-image-web").on('click', function () {
            var lfm = function (options, cb) {
                var route_prefix = '/filemanager';
                window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                window.SetUrl = cb;
            };

            lfm({type: 'image'}, function (url, path) {
                $("#image-review-web").html('<img src="' + path + '" class="w-25">');
                $("#image-select-web").val(path);
            });
        });
    </script>
@stop
