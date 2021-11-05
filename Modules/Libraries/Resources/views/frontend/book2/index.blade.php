@extends('layouts.app')

@section('page_title', 'Sách')

@section('header')
<link rel="stylesheet" href="{{ asset('styles/module/online/css/list.css') }}">
<link rel="stylesheet" href="{{ asset('styles/module/libraries/css/styles-library.css') }}">
<script src="{{ asset('styles/module/libraries/js/frontend_lib.js') }}"></script>
@endsection

@section('content')
<div class="container-fluid">
    <div class="slider-top">
        @foreach($sliders as $slider)
            <img src="{{ image_file($slider->image) }}" style="width: 100%;max-height: 450px"/>
        @endforeach
    </div>
    <div class="content-main" id="content-main">
        <p></p>
        <div class="row">
            <div class="col-md-7">
                <a href="/">{{ trans('app.home') }}</a> &raquo;
                <a href="{{ route('module.libraries') }}">{{ trans('app.libraries') }}</a> &raquo;
                <span>{{ trans('app.book') }}</span>
            </div>
            <div class="col-md-5 text-right">
                <form class="form-inline" id="form-search">
                    <div class="">
                        <select name="search-cate" id="search-cate" class="form-control select2" data-placeholder="{{ trans('app.category') }}">
                            <option value=""></option>
                            @foreach($cate as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ data_locale('Nhập tên sách', 'Enter the title of the book') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('app.search') }}</button>
                </form>
            </div>
        </div>
        <p></p><br>
        <div class="show">
            <div class="slider">
                <div class="row row1">
                    @foreach($books as $book)
                        @php
                            $bookmark = $check_bookmarks($book->id, 1);
                            $registered = $register($book->id);
                        @endphp
                        <div class="col-md-3 block">
                            <div class="image">
                                <a href="javascript:void(0)">
                                    <img src="{{ image_file($book->image) }}" alt=""/>
                                </a>
                                @if($bookmark)
                                    <div class="bookmark"> {{ data_locale('Đánh dấu', 'Bookmark') }}</div>
                                @endif
                                @if($registered && $registered->status == 2)
                                    <div class="borrowed"> {{ data_locale('Đang mượn', 'Borrow') }}</div>
                                @endif
                                @if($registered && $registered->status == 3)
                                    <div class="borrowed"> {{ data_locale('Đã xem', 'Borrowed') }}</div>
                                @endif
                            </div>
                            <div class="desc" style="padding: 19px 24px;">
                                <div class="name">{{ $book->name }}</div>
                                <p class="text-justify">{!! sub_char(strip_tags($book->description), 30) !!}</p>
                                <div class="info"></div>
                                <div class="btn-views">
                                    <a href="{{ route('module.libraries.book.detail', ['id' => $book->id]) }}" class="btn btn-info link-btn-views">{{ trans('app.detail') }}</a>
                                </div>
                                <div class="btn-views">
                                    @if($bookmark)
                                        <form action="{{ route('module.frontend.libraries.remove_bookmark', ['id' => $book->id, 'type' => 1]) }}" method="post" class="form-ajax">
                                            <button type="submit" class="btn btn-info btn-sm">{{ data_locale('Bỏ đánh dấu', 'Unmark') }}</button>
                                        </form>
                                    @else
                                        <form action="{{ route('module.frontend.libraries.save_bookmark', ['id' => $book->id, 'type' => 1]) }}" method="post" class="form-ajax">
                                            <button type="submit" class="btn btn-primary btn-sm">{{ data_locale('Đánh dấu', 'Bookmark') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="title">
                                <a href="{{ route('module.libraries.book.detail', ['id' => $book->id]) }}">{{ sub_char($book->name, 7) }} </a>
                                <div class="time"> {{ trans('app.date_submit') .': '. get_date($book->created_at) }} </div>
                            </div>
                            <div class="footer-block"> </div>
                        </div>
                    @endforeach
                </div>
                <div class="row justify-content-end">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@stop
