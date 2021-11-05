@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.setting') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('backend.google.map') }}">Địa điểm đào tạo</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">Danh sách địa điểm đào tạo</span>
        </h2>
    </div>
@endsection

@section('content')
    <div role="main">
        <form method="post" action="{{ route('backend.google.map.save') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{ $model->id }}">
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 text-right">
                    <div class="btn-group act-btns">
                        @canany(['guide-create', 'guide-edit'])
                        <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                        @endcanany
                        <a href="{{ route('backend.contact') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
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
                                <label for="name">Tên Liên hệ<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="title" class="form-control" value="{{ $model->title }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><label>Kinh độ</label></div>
                            <div class="col-md-6">
                                <input type="text" name="lng" class="form-control" value="{{ $model->lng }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3"><label>Vĩ độ</label></div>
                            <div class="col-md-6">
                                <input type="text" name="lat" class="form-control" value="{{ $model->lat }}">
                            </div>
                        </div>
                        <div class="form-group row" id="select_posts">
                            <div class="col-md-3"><label>Nội dung</label></div>
                            <div class="col-md-6">
                                <textarea name="description" id="content" placeholder="{{ trans('backend.content') }}" class="form-control">{{ $model->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<script type="text/javascript">
    CKEDITOR.replace('content', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>
@stop
