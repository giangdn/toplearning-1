@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.forum'))

@section('content')
    <div class="container">
        <form action="{{ route('module.frontend.forums.form.save', ['id' => $sub_categories_all->id]) }}" method="post" enctype="multipart/form-data" class="form-ajax form-validate">
            @csrf
            <div class="form-group">
                <input type="text" name="title" class="form-control" placeholder="{{ data_locale('Tiêu đề bài viết', 'Article title') }}" required>
            </div>
            <div class="form-group">
                <textarea rows="8" name="content" class="form-control" placeholder="{{ data_locale('Nội dung bài viết', 'Content Articles') }}" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-danger">{{ trans('app.send_new_posts') }}</button>
            </div>
        </form>
    </div>
@endsection
