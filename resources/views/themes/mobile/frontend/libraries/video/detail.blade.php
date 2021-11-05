@extends('themes.mobile.layouts.app')

@section('page_title', 'Video')

@section('content')
    <div class="container">
        <div class="row border-0 mt-2">
            <div class="col-12">
                <video class="w-100" controls autoplay>
                    <source src="{{ $item->getLinkPlay() }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
        <div class="row bg-white pt-2">
            <div class="col-auto align-self-center mt-1">
                <h6 class="mb-2 font-weight-bold">{{ $item->name }}</h6>
                <p class="text-mute">
                    <span>{{ $item->views }} <i class="material-icons vm">remove_red_eye</i></span>
                    <span class="">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
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
