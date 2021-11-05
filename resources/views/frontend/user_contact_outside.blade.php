@extends('layouts.app_outside')

@section('page_title', 'HỆ THỐNG QUẢN LÝ ĐÀO TẠO E-LEARNING')

@section('content')
    <div class="container user_and_company">
        @if (!empty($get_infomation_company))
            <div class="row user_contact">
                <div class="col-12">
                    <h3 class="name_company"><strong>{{ $get_infomation_company ? $get_infomation_company->title : '' }}</strong></h3>
                    <div class="description_company">
                        {!! $get_infomation_company ? $get_infomation_company->content : '' !!}
                    </div>
                </div>
            </div>
        @endif

        <form method="post" action="{{ route('save_user_contact') }}" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
            @csrf
            <div class="wrapped_contact row">
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-sm-3 control-label">
                            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="title" class="form-control" value="{{ $model->title }}" placeholder="-- Nhập tiêu đề --">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label for="content">Nội dung <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6">
                            <textarea name="content" id="content" class="form-control">{{ $model->content }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                        </div>
                        <div class="col-md-6 send_contact">
                            <button type="submit" class="btn btn-info">Gửi</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        /*CKEDITOR.replace('content', {
            filebrowserImageBrowseUrl: '/filemanager?type=image',
            filebrowserBrowseUrl: '/filemanager?type=file',
            filebrowserUploadUrl : null, //disable upload tab
            filebrowserImageUploadUrl : null, //disable upload tab
            filebrowserFlashUploadUrl : null, //disable upload tab
        });*/
    </script>
@endsection
