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
                        <a href="{{ route('module.frontend.forums.thread', ['id' => $thread->id]) }}">{{ $thread->title }}</a>
                        <i class="uil uil-angle-right"></i>
                        <span class="font-weight-bold">{{ trans('app.comment') }}</span>
                    </h2>
                </div>
            </div>
        </div>
        <p></p>
        <div class="row content-fill">
            <div id="article" style="width: 100%;">
                <form action="{{route('module.frontend.forums.comment.update',['id' => $comment->id])}}" method="POST" enctype="multipart/form-data" class="form-validate form-ajax">
                    @csrf
                    <div class="form-group">
                        <textarea rows="8" id="editor" name="comment" class="form-control" placeholder="{{ data_locale('Nội dung bài viết', 'Content Articles') }}" required>{{ $comment->comment }}</textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn_adcart">{{ trans('app.comment') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('footer')
    <script type="text/javascript">
        $("form").submit( function(e) {
            var messageLength = CKEDITOR.instances['editor'].getData().replace(/<[^>]*>/gi, '').length;
            if( !messageLength ) {
                alert( 'Chưa nhập nội dung' );
                e.preventDefault();
            }
        });
    </script>
@endsection
