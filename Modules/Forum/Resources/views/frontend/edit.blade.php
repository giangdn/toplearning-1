@extends('layouts.app')

@section('page_title', 'Diễn đàn')

@section('content')

<div class="container-fluid">
    <div class="content-main" id="content-main">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content forum-container">
                    <h2 class="st_title"><i class="uil uil-apps"></i>
                        <a href="{{ route('module.frontend.forums') }}">{{ trans('app.forum') }}</a>
                        <i class="uil uil-angle-right"></i>
                        <a href="{{ route('module.frontend.forums.topic', ['id' => $forum->id]) }}">{{ $forum->name }}</a>
                        <i class="uil uil-angle-right"></i>
                        <span class="font-weight-bold">{{trans('backend.edit_post')}}</span>
                    </h2>
                </div>
            </div>
        </div>
        <p></p>
        <div class="row content-fill">
            <div id="article" style="width: 100%;">
                <form action="{{route('module.frontend.forums.update',['id' => $thread->id])}}" method="POST" enctype="multipart/form-data" class="form-validate form-ajax">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="title" class="form-control" placeholder="{{ data_locale('Tiêu đề bài viết', 'Article title') }}" value="{{ $thread->title }}">
                    </div>
                    <div class="form-group">
                        <input type="text" name="hashtag" class="form-control" placeholder="hashtag" value="{{ $thread->hashtag }}">
                    </div>
                    <div class="form-group">
                        <textarea rows="8" id="editor" name="content" class="form-control" placeholder="{{ data_locale('Nội dung bài viết', 'Content Articles') }}">{{ $thread->content }}</textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn_adcart">{{ trans('app.send_new_posts') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
