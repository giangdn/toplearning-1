@extends('layouts.app')

@section('page_title', 'Ebook')

@section('header')
    <script src="{{ asset('styles/module/libraries/js/frontend_lib.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/online/css/list.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/module/libraries/css/styles-library.css') }}">
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
                <span>Ebook</span>
            </div>
            <div class="col-md-5">
                <form class="form-inline" id="form-search">
                    <div class="">
                        <select name="search-cate" id="search-cate" class="form-control select2" data-placeholder="{{ trans('app.category') }}">
                            <option value=""></option>
                            @foreach($cate as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="text" name="search" value="" class="form-control" placeholder="{{ data_locale('Nhập tên ebook', 'Enter the ebook name') }}">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('app.search') }}</button>
                </form>
            </div>
        </div>
        <p></p><br>
        <div class="show">
            <div class="slider">
                <div class="row row1">
                    @foreach($ebooks as $ebook)
                        @php
                            $libraries = $libraries_obj(Auth::id(), $ebook->id, 2);
                            $bookmark = $check_bookmarks($ebook->id, 2);
                        @endphp
                        <div class="col-md-3 block">
                            <div class="image">
                                <a href="javascript:void(0)">
                                    <img src="{{ image_file($ebook->image) }}" alt=""/>
                                </a>
                                @if($bookmark)
                                    <div class="bookmark"> {{ data_locale('Đánh dấu', 'Bookmark') }}</div>
                                @endif
                                @if($ebook->views > 0)
                                    <div class="borrowed"> {{ data_locale('Đã xem', 'Has read') }}</div>
                                @endif
                            </div>
                            <div class="desc" style="padding: 19px 24px;">
                                <div class="name">{{ $ebook->name }}</div>
                                <p class="text-justify">{!! sub_char(strip_tags($ebook->description), 30) !!}</p>
                                <div class="info"></div>
                                <div class="btn-views">
                                    <a href="{{ route('module.libraries.ebook.detail', ['id' => $ebook->id]) }}" class="btn btn-info
                                    link-btn-views">{{ trans('app.detail') }}</a>
                                </div>
                                @if($ebook->attachment)
                                    <div class="btn-views mt-1">
                                        @if(isset($libraries->status) && ($libraries->status == 2 || $libraries->status == 3))
                                        <a href="{{ $ebook->getLinkDownload() }}" class="btn btn-info link-btn-views" style="background:#af8842;"><i class="fa fa-download"></i> {{ trans('app.download') }}</a>
                                        @endif
                                    </div>
                                @endif
                                @if($ebook->isFilePdf())
                                    <div class="btn-views mt-1">
                                        @if(isset($libraries->status) && ($libraries->status == 1 || $libraries->status == 3))
                                        <a target="_blank" href="{{ $ebook->getLinkView() }}" class="btn btn-info link-btn-views click-view-ebook"
                                           style="background:
                                        #42afaf; " data-id="{{$ebook->id}}"> <i class="fa fa-eye"></i> {{ trans('app.watch_online') }}</a>
                                        @endif
                                    </div>
                                @endif
                                <div class="btn-views">
                                    @if($bookmark)
                                        <form action="{{ route('module.frontend.libraries.remove_bookmark', ['id' => $ebook->id, 'type' => 2]) }}" method="post" class="form-ajax">
                                            <button type="submit" class="btn btn-info btn-sm">{{ data_locale('Bỏ đánh dấu', 'Unmark') }}</button>
                                        </form>
                                    @else
                                        <form action="{{ route('module.frontend.libraries.save_bookmark', ['id' => $ebook->id, 'type' => 2]) }}" method="post" class="form-ajax">
                                            <button type="submit" class="btn btn-primary btn-sm">{{ data_locale('Đánh dấu', 'Bookmark') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="title">
                                <a href="{{ route('module.libraries.ebook.detail', ['id' => $ebook->id]) }}">{{ sub_char($ebook->name, 7) }} </a>
                                <div class="time">{{ trans('app.date_submit') .': '. get_date($ebook->created_at) }} </div>
                            </div>
                            <div class="footer-block"></div>
                        </div>
                    @endforeach
                </div>
                <div class="row justify-content-end">
                    {{ $ebooks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
