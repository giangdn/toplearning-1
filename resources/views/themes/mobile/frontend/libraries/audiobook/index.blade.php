@extends('themes.mobile.layouts.app')

@section('page_title', 'Sách nói')

@section('content')
    <div class="container">
        @if($audiobook)
            <div class="row">
                @foreach($audiobook as $key => $item)
                    <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-3 p-1">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6 p-1">
                                        <img src="{{ image_file($item->image) }}" alt="" class="w-100" style="height: 200px;">
                                    </div>
                                    <div class="col-12 col-md-6 p-1 align-self-center">
                                        <p class="text-mute">
                                            <span>{{ $item->views }} <i class="material-icons vm small">remove_red_eye</i></span>
                                            <span class="float-right small">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                                        </p>
                                        <a href="{{ route('module.libraries.audiobook.detail', ['id' => $item->id]) }}">
                                            <h6 class="mb-2 font-weight-normal">{{ sub_char($item->name, 8) }}</h6>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="row">
            <div class="col-6">
                @if($audiobook->previousPageUrl())
                <a href="{{ $audiobook->previousPageUrl() }}" class="bp_left">
                    <i class="material-icons">navigate_before</i> @lang('app.previous')
                </a>
                @endif
            </div>
            <div class="col-6 text-right">
                @if($audiobook->nextPageUrl())
                <a href="{{ $audiobook->nextPageUrl() }}" class="bp_right">
                    @lang('app.next') <i class="material-icons">navigate_next</i>
                </a>
                @endif
            </div>
        </div>
    </div>
@endsection
