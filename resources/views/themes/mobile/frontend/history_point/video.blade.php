@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.accumulated_from_video'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="list-group list-group-flush">
                    @if(count($get_history_video) > 0)
                        @foreach($get_history_video as $item)
                            @php
                                $score_view = \Modules\DailyTraining\Entities\DailyTrainingVideo::getScoreView($item->view);
                                $score_comment = \Modules\DailyTraining\Entities\DailyTrainingVideo::getScoreComment($item->id);
                            @endphp
                            <div class="row mb-1 bg-white shadow border">
                                <div class="col-auto align-self-center">
                                    <img src="{{ asset('themes/mobile/img/video.png') }}" alt="" class="avatar-40">
                                </div>
                                <div class="col pl-0">
                                    <p class="mb-0">{{ $item->name }}</p>
                                    <p class="text-mute mt-1 mb-0">
                                        {{ get_date($item->created_at) }}
                                    </p>
                                    <p class="text-mute mt-1 mb-1">
                                        @if($score_view)
                                            {{ $score_view }} <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                            <span class="float-right">
                                                {{ trans('app.view') }}
                                            </span>
                                        @endif
                                    </p>
                                    <p class="text-mute mt-1 mb-1">
                                        @if($score_comment)
                                            {{ $score_comment }} <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                            <span class="float-right">
                                                {{ trans('app.comment') }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        <div class="row mt-2">
                            <div class="col-12 px-0 text-right">
                                {{ $get_history_video->links('themes/mobile/layouts/pagination') }}
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
