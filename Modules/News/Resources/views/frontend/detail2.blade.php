@extends('layouts.app')

@section('page_title', 'Tin Tức')

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/laraberg/css/laraberg.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/module/news/css/article.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/module/news/css/style-news.css') }}">
    <script type="text/javascript" src="{{ asset('styles/module/news/js/news.js') }}"></script>
    <script src="{{ asset('styles/module/quiz/js/scrollreveal.min.js') }}" type="text/javascript"></script>
    <script language="javascript" src="{{ asset('styles/module/news/js/comment.js') }}"></script>
@endsection

@section('content')
<div class="container-fluid" id="news-content">
    <div class="slider-top">
        @foreach($sliders as $slider)
            <img src="{{ image_file($slider->image) }}" style="width: 100%;max-height: 450px"/>
        @endforeach
    </div>
    <p></p>
    <div class="row">
        <div class="col-md-12">
            <a href="/"> {{ trans('app.home') }} </a> &raquo; <a href="{{ route('module.news') }}"> {{ trans('app.news') }} </a> &raquo; {{ $item->title }}
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-sm-9 body_news">
                    <div id="article">
                        <article>
                            <div class="title_news">
                                <h2 class="article-title">{{ $item->title }}</h2>
                                <div class="row-fluid">
                                    <div class="article-info col-md-10">
                                        <strong style=" color:DodgerBlue; font-family: Calibri; font-size: 20px; font-weight: bold;">
                                            {{ trans('app.writer') .': ' }}
                                        </strong>
                                        <strong style="margin-right:5px;">{{ $created_by->lastname. " " . $created_by->firstname }}</strong>
                                        |
                                        <strong style="color:Red; margin-left:5px;
                                                font-family: Calibri;
                                                font-size: 20px;
                                                font-weight: bold;">{{ trans('app.date_submit') .': ' }}</strong>
                                        <strong style="margin-right:7px;font-weight: 100;">{{ get_date($item->created_at) }} </strong>
                                        |
                                        <strong style="font-weight: 100;">{{ trans('app.view') .': '. $item->views }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="text-justify m-2">
                                    {!! $item->content !!}
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
                <div class="col-sm-3 news_news">
                    <div class="silebar border">
                        <h3 class="title-block btn-primary">{{ trans('app.most_view_news') }}</h3>
                        @foreach($views_max as $view)
                            <div class="block-news-mini row">
                                <div class="col-md-12">
                                    <img src="{{ image_file($view->image) }}" />
                                </div>
                                <div class="col-md-12">
                                    <div><a href="{{ route('module.news.detail', ['id' => $view->id]) }}" class="title">{{ $view->title }}</a></div>
                                    <span class="info"><i class="fa fa-calculator"></i> {{ get_date($view->created_at) }} <i class="fa fa-eye"></i> {{$view->views}} </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="silebar border">
                        <h3 class="title-block btn-primary">{{ trans('app.same_category') }}</h3>
                        @foreach($categories as $category)
                            <div class="block-news-mini row">
                                <div class="col-md-12">
                                    <img src="{{ image_file($category->image) }}" />
                                </div>
                                <div class="col-md-12">
                                    <div><a href="{{ route('module.news.detail', ['id' => $category->id]) }}" class="title">{{ $category->title }}</a></div>
                                    <span class="info"><i class="fa fa-calculator"></i> {{ get_date($category->created_at) }} <i class="fa fa-eye"></i> {{$category->views}} </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
