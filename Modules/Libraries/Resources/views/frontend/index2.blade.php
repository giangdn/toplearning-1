@extends('layouts.app')

@section('page_title', 'Thư viện')

@section('header')
<link rel="stylesheet" href="{{ asset('styles/module/online/css/list.css') }}">
<link rel="stylesheet" href="{{ asset('styles/module/libraries/css/styles-library.css') }}">
<script src="{{ asset('styles/module/libraries/js/frontend_lib.js') }}"></script>
@endsection

@section('content')

<div class="container-fluid">
    <div class="slider-top">
        @foreach($sliders as $slider)
            <img src="{{ image_file($slider->image) }}" style="width: 100%; max-height: 450px"/>
        @endforeach
    </div>
    <div class="content-main" id="content-main" style="background: none !important;">
        <a href="/">{{ trans('app.home') }}</a> &raquo; <span>{{ trans('app.libraries') }}</span>
        <h6>
            <img src="{{ asset('styles/images/button.png') }}" style="width: 250px; height: 70px;">
            <div class="title"> {{ data_locale('SÁCH', 'BOOK') }} <br> {{ data_locale('Khuyên đọc', 'You should read') }} </div>
        </h6>
        <div class="show">
            <div class="slider">
                <div class="row row1">
                    @foreach($new_books as $new_book)
                        @php
                            $bookmark = $check_bookmarks($new_book->id, 1);
                            $registered = $register($new_book->id);
                        @endphp
                        <div class="col-md-3 block">
                            <div class="image">
                                <a href="javascript:void(0)">
                                    <img src="{{ image_file($new_book->image) }}" alt=""/>
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
                                <div class="name">{{ $new_book->name }}</div>
                                <p class="text-justify">{!! sub_char(strip_tags($new_book->description), 30) !!} </p>
                                <div class="info"></div>
                                <div class="btn-views">
                                    <a href="{{ route('module.libraries.book.detail', ['id' => $new_book->id]) }}" class="btn btn-success"><i class="fa fa-edit"></i> {{ trans('app.detail') }}</a>
                                </div>
                                <div class="btn-views">
                                    @if($bookmark)
                                        <form action="{{ route('module.frontend.libraries.remove_bookmark', ['id' => $new_book->id, 'type' => 1]) }}" method="post" class="form-ajax">
                                            <button type="submit" class="btn btn-info btn-sm">{{ data_locale('Bỏ đánh dấu', 'Unmark') }}</button>
                                        </form>
                                    @else
                                        <form action="{{ route('module.frontend.libraries.save_bookmark', ['id' => $new_book->id, 'type' => 1]) }}" method="post" class="form-ajax">
                                            <button type="submit" class="btn btn-primary btn-sm">{{ data_locale('Đánh dấu', 'Bookmark') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="title">
                                <a href="{{ route('module.libraries.book.detail', ['id' => $new_book->id]) }}">{{ sub_char($new_book->name, 7) }} </a>
                                <div class="time">{{ trans('app.date_submit') .': '. get_date($new_book->created_at) }} </div>
                            </div>
                            <div class="footer-block"></div>
                        </div>
                    @endforeach
                </div>
                <div class="paging"></div>
            </div>
        </div>
        <div class="button-view-all">
            <a href="{{ route('module.frontend.libraries.book',['id' => 0]) }}" class="view-all"><span>{{ trans('app.view_all') }}</span></a>
        </div>

        <h6>
            <img src="{{ asset('styles/images/button.png') }}" style="width: 300px; height: 80px;">
            <div class="title"> EBOOK <br>
                {{ data_locale('Đọc và tham khảo, ', 'Read, referrence ') }}
                <br>
                {{data_locale('phát triển bản thân', 'and develop personal') }}
            </div>
        </h6>
        <div class="show">
            <div class="slider">
                <div class="row row1">
                    @foreach($new_ebooks as $new_ebook)
                        @php
                            $libraries = $libraries_obj(Auth::id(), $new_ebook->id, 2);
                            $bookmark = $check_bookmarks($new_ebook->id, 2);
                        @endphp
                        <div class="col-md-3 block">
                            <div class="image">
                                <a href="javascript:void(0)">
                                    <img src="{{ image_file($new_ebook->image) }}" alt=""/>
                                </a>
                                @if($bookmark)
                                    <div class="bookmark"> {{ data_locale('Đánh dấu', 'Bookmark') }}</div>
                                @endif
                                @if($new_ebook->views > 0)
                                    <div class="borrowed"> {{ data_locale('Đã xem', 'Has read') }}</div>
                                @endif
                            </div>
                            <div class="desc" style="padding: 19px 24px;">
                                <div class="name">{{ $new_ebook->name }}</div>
                                <p class="text-justify">{!! sub_char(strip_tags($new_ebook->description), 30)  !!}</p>
                                <div class="info"></div>
                                <div class="btn-views">
                                    <a href="{{ route('module.libraries.ebook.detail', ['id' => $new_ebook->id]) }}" class="btn btn-success"><i class="fa fa-edit"></i> {{ trans('app.detail') }}</a>
                                </div>
                                @if($new_ebook->attachment)
                                    <div class="btn-views">
                                        @if(isset($libraries->status) && ($libraries->status == 2 || $libraries->status == 3))
                                        <a href="{{ $new_ebook->getLinkDownload() }}" class="btn btn-primary">
                                            <i class="fa fa-download"></i> {{ trans('app.download') }}
                                        </a>
                                        @endif
                                    </div>
                                @endif

                                @if($new_ebook->isFilePdf())
                                <div class="btn-views">
                                    @if(isset($libraries->status) && ($libraries->status == 1 || $libraries->status == 3))
                                    <a target="_blank" href="{{ $new_ebook->getLinkView() }}" class="btn btn-info click-view-ebook" data-id="{{$new_ebook->id}}">
                                        <i class="fa fa-eye"></i> {{ trans('app.watch_online') }}
                                    </a>
                                    @endif
                                </div>
                                @endif
                                <div class="btn-views">
                                    @if($bookmark)
                                        <form action="{{ route('module.frontend.libraries.remove_bookmark', ['id' => $new_ebook->id, 'type' => 2]) }}" method="post" class="form-ajax">
                                            <button type="submit" class="btn btn-info btn-sm">{{ data_locale('Bỏ đánh dấu', 'Unmark') }}</button>
                                        </form>
                                    @else
                                        <form action="{{ route('module.frontend.libraries.save_bookmark', ['id' => $new_ebook->id, 'type' => 2]) }}" method="post" class="form-ajax">
                                            <button type="submit" class="btn btn-primary btn-sm">{{ data_locale('Đánh dấu', 'Bookmark') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="title">
                                <a href="{{ route('module.libraries.ebook.detail', ['id' => $new_ebook->id]) }}"> {{ sub_char($new_ebook->name, 7) }} </a>
                                <div class="time">{{ trans('app.date_submit') .': '. get_date($new_ebook->created_at) }} </div>
                            </div>
                            <div class="footer-block"> </div>
                        </div>
                    @endforeach
                </div>
                <div class="paging"> </div>
            </div>
        </div>
        <div class="button-view-all">
            <a href="{{ route('module.frontend.libraries.ebook',['id' => 0]) }}" class="view-all"><span>{{ trans('app.view_all') }}</span></a>
        </div>

        <h6>
            <img src="{{ asset('styles/images/button.png') }}" style="width: 250px; height: 70px;">
            <div class="title"> {{ data_locale('TÀI LIỆU', 'DOCUMENT') }} </div>
        </h6>
        <div class="show">
            <div class="slider">
                <div class="row row1">
                    @foreach($new_documents as $new_document)
                        @php
                            $libraries = $libraries_obj(Auth::id(), $new_document->id, 3);
                            $bookmark = $check_bookmarks($new_document->id, 3);
                        @endphp
                        <div class="col-md-3 block">
                            <div class="image">
                                <a href="javascript:void(0)">
                                    <img src="{{ image_file($new_document->image) }}" alt=""/>
                                </a>
                                @if($bookmark)
                                    <div class="bookmark"> {{ data_locale('Đánh dấu', 'Bookmark') }}</div>
                                @endif
                                @if($new_document->views > 0)
                                    <div class="borrowed"> {{ data_locale('Đã xem', 'Has read') }}</div>
                                @endif
                            </div>
                            <div class="desc" style="padding: 19px 24px;">
                                <div class="name">{{ $new_document->name }}</div>
                                <p class="text-justify">{!! sub_char(strip_tags($new_document->description), 30)  !!}</p>
                                <div class="info"></div>
                                <div class="btn-views">
                                    <a href="{{ route('module.libraries.document.detail', ['id' => $new_document->id]) }}" class="btn btn-success"><i class="fa fa-edit"></i> {{ trans('app.detail') }}</a>
                                </div>
                                @if($new_document->attachment)
                                    <div class="btn-views">
                                        @if(isset($libraries->status) && ($libraries->status == 2 || $libraries->status == 3))
                                        <a href="{{ $new_document->getLinkDownload() }}" class="btn btn-primary">
                                            <i class="fa fa-download"></i> {{ trans('app.download') }}
                                        </a>
                                        @endif
                                    </div>
                                @endif

                                @if($new_document->isFilePdf())
                                    <div class="btn-views">
                                        @if(isset($libraries->status) && ($libraries->status == 1 || $libraries->status == 3))
                                        <a target="_blank" href="{{ $new_document->getLinkView() }}" class="btn btn-info click-view-doc" data-id="{{$new_document->id}}">
                                            <i class="fa fa-eye"></i> {{ trans('app.watch_online') }}
                                        </a>
                                        @endif
                                    </div>
                                @endif
                                <div class="btn-views">
                                    @if($bookmark)
                                        <form action="{{ route('module.frontend.libraries.remove_bookmark', ['id' => $new_document->id, 'type' => 3]) }}" method="post" class="form-ajax">
                                            <button type="submit" class="btn btn-info btn-sm">{{ data_locale('Bỏ đánh dấu', 'Unmark') }}</button>
                                        </form>
                                    @else
                                        <form action="{{ route('module.frontend.libraries.save_bookmark', ['id' => $new_document->id, 'type' => 3]) }}" method="post" class="form-ajax">
                                            <button type="submit" class="btn btn-primary btn-sm">{{ data_locale('Đánh dấu', 'Bookmark') }}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="title">
                                <a href="{{ route('module.libraries.document.detail', ['id' => $new_document->id]) }}">{{ sub_char($new_document->name, 7) }}</a>
                                <div class="time">{{ trans('app.date_submit') .': '. get_date($new_document->created_at) }} </div>
                            </div>
                            <div class="footer-block"></div>
                        </div>
                    @endforeach
                </div>
                <div class="paging"></div>
            </div>
        </div>
        <div class="button-view-all">
            <a href="{{ route('module.frontend.libraries.document',['id' => 0]) }}" class="view-all"><span>{{ trans('app.view_all') }}</span></a>
        </div>
        <p></p>
    </div>
</div>

@stop
