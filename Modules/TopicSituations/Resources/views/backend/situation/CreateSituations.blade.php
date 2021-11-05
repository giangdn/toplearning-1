@extends('layouts.backend')

@section('page_title', 'Xử lý tình huống')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
        <a href="{{route('module.topic_situations')}}">Xử lý tình huống</a>
        <i class="uil uil-angle-right"></i>
        <span>Thêm mới Xử lý tình huống</span>
    </h2>
</div>
<form method="POST" action="{{ route('module.save.situations',['id' => $topic_id]) }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
        </div>
        <div class="col-md-4 text-right mb-3">
            <div class="btn-group act-btns">
                @can('situation-create')
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                @endcan
                <a href="{{ route('module.topic_situations') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</a>
            </div>
        </div>
    </div>
    <div class="row" id="armorial_id">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="name_situations">Tên Xử lý tình huống <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="name_situations" type="text" class="form-control" value="" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="code_situations">Mã Xử lý tình huống <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <input name="code_situations" type="text" class="form-control" value="" required>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="description_situations">Mô tả <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-6">
                    <textarea id="content" name="description_situations" class="form-control" placeholder="Mô tả"></textarea>
                </div>
            </div>
        </div>  
    </div>
</form> 
<script>
    CKEDITOR.replace('content', {
        filebrowserImageBrowseUrl: '/filemanager?type=image',
        filebrowserBrowseUrl: '/filemanager?type=file',
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>
@endsection

