@extends('layouts.app_outside')

@section('page_title', $item->title)

@section('content')
    <div class="row" id="list-news">
        <div class="title-main title-line"><h2>{{ $item->title }}</h2></div>

        <div class="container wrap-content">
            <div class="p-2">
                {!! $item->description !!}
            </div>
        </div>
    </div>
@stop
