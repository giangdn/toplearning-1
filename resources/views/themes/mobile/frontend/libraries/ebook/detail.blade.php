@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.ebook'))

@section('content')
    <div class="container">
        <div class="row border-0 p-2 bg-image">
            <div class="col-3"></div>
            <div class="col-6 p-0">
                <img src="{{ image_file($item->image) }}" alt="" class="w-100">
            </div>
            <div class="col-3"></div>
        </div>
        <div class="row bg-white pt-5">
            <div class="col-auto align-self-center mt-1">
                <h6 class="mb-2 font-weight-bold">{{ $item->name }}</h6>
                <p class="text-mute">
                    <span>{{ $item->views }} <i class="material-icons vm">remove_red_eye</i></span>
                    <span class="pl-2">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                    <br>
                    <span class="text-center">@lang('app.download') : {{ $item->download }} </span>
                    <br>
                    @if($item->isFilePdf())
                        <a href="{{ route('module.libraries.view_pdf', ['id' => $item->id]) }}" class="btn btn-warning click-view-doc" data-id="{{$item->id}}" ><i class="material-icons vm">remove_red_eye</i> @lang('app.watch_online')</a>
                    @endif
                </p>
            </div>
        </div>
        <div class="row pt-1">
            <div class="col-md-12">
                <h5>@lang('app.description')</h5>
                <img class="line-title" src="{{ asset('images/line.svg') }}" alt="">
                <br>
                <p class="text-justify">{!! $item->description !!}</p>
            </div>
        </div>
    </div>
@endsection
