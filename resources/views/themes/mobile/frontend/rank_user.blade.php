@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.rank'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 px-0">
                @if($rank->count() > 0)
                    <ul class="list-group list-group-flush border-bottom">
                        @foreach($rank as $item)
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto pr-0">
                                        <img src="{{ \App\Profile::avatar($item->user_id) }}" alt="" class="avatar avatar-50 border-0">
                                    </div>
                                    <div class="col align-self-center">
                                        <p class="mb-1">
                                            {{ \App\Profile::fullname($item->user_id) }}
                                        </p>
                                        <p class="text-mute">
                                            ({{ \App\Profile::usercode($item->user_id) }})
                                            <span class="float-right">
                                                {{ $item->point }}
                                                <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-center bg-white">@lang('app.not_found')</p>
                @endif
            </div>
        </div>
    </div>
@endsection
