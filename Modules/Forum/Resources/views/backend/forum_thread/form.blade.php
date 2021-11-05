@extends('layouts.backend')

@section('page_title', 'Duyệt bài đăng')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
<div class="mb-4 forum-container">
    <h2 class="st_title"><i class="uil uil-apps"></i>
        {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.forum.category') }}">{{ trans('backend.forum') }}</a>
        <i class="uil uil-angle-right"></i>
        <a href="{{ route('module.forum', ['cate_id' => $cate->id]) }}">{{$cate->name}}</a>
        <i class="uil uil-angle-right"></i>
        <span> {{ trans('backend.add_new') }}: {{ $forum->name }}</span>
    </h2>
</div>
<div role="main">
    <form method="POST" action="{{ route('module.forum.thread.save',['cate_id' => $cate->id,'forum_id' => $forum->id]) }}" 
        class="form-validate form-ajax" 
        role="form" 
        enctype="multipart/form-data"
    >
        <input type="hidden" name="id" value="{{ $model->id }}">
        <input type="hidden" name="main_article" value="1">
        <input type="hidden" name="status" value="1">
        <input type="hidden" name="forum_id" value="{{ $forum->id }}">
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    <a href="{{ route('module.forum.thread', ['cate_id' => $cate->id,'forum_id' => $forum->id]) }}" class="btn btn-warning">
                        <i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}
                    </a>
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
                                    <label for="title">{{ trans('backend.enter_title_thread') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input name="title" type="text" class="form-control" value="{{ $model->title }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="title">Hashtag <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input name="hashtag" type="text" class="form-control" value="{{ $model->hashtag }}" required>
                                </div>
                            </div>
    
                            <!-- <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="status" class="hastip" data-toggle="tooltip" data-placement="right" title="Chọn trạng thái">Trạng thái</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="radio" name="status" value="0" >&nbsp;&nbsp;Tắt
                                    <input type="radio" name="status" value="1" >&nbsp;&nbsp;Bật
                                </div>
                            </div> -->

                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label for="content">{{ trans('backend.enter_content_thread') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <textarea name="content" id="content" placeholder="{{ trans('backend.content') }}" class="form-control">{{ $model->content }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
<script type="text/javascript">
    CKEDITOR.replace('content', {
        filebrowserUploadUrl : null, //disable upload tab
        filebrowserImageUploadUrl : null, //disable upload tab
        filebrowserFlashUploadUrl : null, //disable upload tab
    });
</script>

<script type="text/javascript" src="{{ asset('styles/module/forum/js/forum.js') }}"></script>
@endsection
