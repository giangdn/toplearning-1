@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.feedback') }}">Quản lý phản hồi</a> <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')

    <div role="main">

        <form method="post" action="{{ route('backend.feedback.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @canany(['feedback-create', 'feedback-edit'])
                            <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                        @endcanany
                        <a href="{{ route('backend.feedback') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="image">Tên </label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="name" value="{{ $model->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="image">{{trans('backend.picture')}}</label>
                            </div>

                            <div class="col-sm-6">
                                <a href="javascript:void(0)" id="select-image">{{trans('backend.choose_picture')}}</a>
                                <div id="image-review">@if($model->image) <img src="{{ image_file($model->image) }}" class="w-25"> @endif</div>
                                <input type="hidden" class="form-control" name="image" id="image-select" value="{{ $model->image }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="position">Chức vụ</label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="position" value="{{ $model->position }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="star"> Số sao</label>
                            </div>
                            <div class="col-sm-6">
                                <select name="star" class="form-control select2" data-placeholder="Chọn số sao">
                                    <option value=""></option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $model->star == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <label for="content">{{ trans('backend.content') }}</label>
                            </div>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="content">{{ $model->content }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

<script type="text/javascript">
    $("#select-image").on('click', function () {
        var lfm = function (options, cb) {
            var route_prefix = '/filemanager';
            window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
            window.SetUrl = cb;
        };

        lfm({type: 'image'}, function (url, path) {
            $("#image-review").html('<img src="'+ path +'" class="w-25">');
            $("#image-select").val(path);
        });
    });
</script>
@stop
