@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.accumulated_from_bonus_points'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="list-group list-group-flush">
                    @if($donate_points)
                        <div class="row mb-1 bg-white shadow border">
                            <div class="col-auto align-self-center">
                                <img src="{{ asset('themes/mobile/img/promotion.png') }}" alt="" class="avatar-40">
                            </div>
                            <div class="col pl-0">
                                <p class="mb-0">{{ \App\Profile::fullname($donate_points->user_id) .' ('. \App\Profile::usercode($donate_points->user_id) .')' }}</p>
                                <p class="text-mute mt-1 mb-1">
                                    {{ $donate_points->score }} <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                </p>
                                <p class="text-mute mt-1 mb-1">
                                    {{ $donate_points->note }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12 text-center">
                                <span>@lang('app.not_found')</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
