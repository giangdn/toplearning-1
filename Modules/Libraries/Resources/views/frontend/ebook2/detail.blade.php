@extends('layouts.app')

@section('page_title', 'Ebook')

@section('header')
<link rel="stylesheet" href="{{ asset('styles/module/online/css/detail.css') }}">
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
    @php
        $libraries = $libraries_obj(Auth::id(), $item->id, 2);
    @endphp
    <div class="content-main" id="content-main">
        <a href="/">{{ trans('app.home') }}</a> &raquo;
        <a href="{{ route('module.libraries') }}">{{ trans('app.libraries') }}</a> &raquo;
        <a href="{{ route('module.frontend.libraries.ebook',['id' => 0]) }}">Ebook</a> &raquo;
        <span>{{ $item->name}}</span>

        <h4 class="title-top">
            <span class="red" style="text-transform: uppercase;">{{ $item->name}}</span>
        </h4>
        <div class="row row-info">
            <div class="col-md-3">
                <img src="{{ image_file($item->image) }}" alt="" style="width: 100%;"/>
            </div>
            <div class="col-md-6">
                <div class="item">{{ trans('app.date_submit') .': '. get_date($item->created_at) }}</div>
                <div class="item">{{ trans('app.view') .': '. $item->views }}</div>
                <div class="item">{{ trans('app.posted_by') .': '. $created_by->lastname. " " . $created_by->firstname }}</div>
                <div class="item">
                    @if($item->attachment)
                        @if(isset($libraries->status) && ($libraries->status == 2 || $libraries->status == 3))
                        <a class="btn btn-info" href="{{ $item->getLinkDownload() }}"><i class="fa fa-download"></i> {{ trans('app.download') }}</a>
                        @endif
                    @endif

                    @if($item->isFilePdf())
                        @if(isset($libraries->status) && ($libraries->status == 1 || $libraries->status == 3))
                        <a target="_blank" href="{{ $item->getLinkView() }}" class="btn btn-primary click-view-ebook" data-id="{{ $item->id }}"> <i class="fa fa-eye"></i> {{ trans('app.watch_online') }}
                        </a>
                        @endif
                    @endif
                </div>
                <div class="item">
                    <b>{{ trans('app.description') }}</b>
                    <p class="text-justify" style="overflow-y: auto; max-height: 279px; color: #000; padding-right: 5px;">{!! strip_tags($item->description) !!}</p>
                </div>
            </div>
            <div class="col-md-3 post-other">
                <h3>{{ trans('app.same_category') }}</h3>
                @foreach($categories as $category)
                    <div class="block-item">
                        <div class="image">
                            <img src="{{ image_file($category->image) }}" style="width: 100%;">
                        </div>
                        <div class="content m-2">
                            <div class="name"><a href="{{ route('module.libraries.ebook.detail', ['id' => $category->id]) }}">{{ $category->name}}</a></div>
                            <div class="description">{!! sub_char($category->description, 30) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop
