@extends('layouts.app_outside')

@section('page_title', trans('app.news'))

@section('content')
    <div class="row" id="list-news">
        <div class="title-main title-line"><h2>Tin tức nổi bật</h2></div>

        <div class="wrap-content">
            <div class="list-relative-products">
                @foreach($get_hot_news as $item)
                <div class="box-rel-products">
                    <a href="{{ route('detail_home_outside', ['id' => $item->id, 'type' => $type]) }}">
                        <div class="pic-img">
                            <img src="{{ image_file($item->image) }}" alt="{{ $item->title }}">
                        </div>
                        <div class="text-rel-products"><h3>{{ $item->title }}</h3></div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@stop
