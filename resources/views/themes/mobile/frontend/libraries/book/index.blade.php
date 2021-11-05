@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.book'))

@section('content')
    <div class="container">
        @if($books)
            <div class="row">
                @foreach($books as $key => $item)
                    <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-3 p-1">
                        <div class="card shadow border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6 p-1">
                                        <img src="{{ image_file($item->image) }}" alt="" class="w-100" style="height: 220px;object-fit: cover">
                                    </div>
                                    <div class="col-12 col-md-6 p-1 align-self-center">
                                        <p class="text-mute">
                                            <span>{{ $item->views }} <i class="material-icons vm small">remove_red_eye</i></span>
                                            <span class="float-right small">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                                        </p>
                                        <a href="{{ route('module.libraries.book.detail', ['id' => $item->id]) }}">
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
                @if($books->previousPageUrl())
                <a href="{{ $books->previousPageUrl() }}" class="bp_left">
                    <i class="material-icons">navigate_before</i> @lang('app.previous')
                </a>
                @endif
            </div>
            <div class="col-6 text-right">
                @if($books->nextPageUrl())
                <a href="{{ $books->nextPageUrl() }}" class="bp_right">
                    @lang('app.next') <i class="material-icons">navigate_next</i>
                </a>
                @endif
            </div>
        </div>
    </div>
@endsection
