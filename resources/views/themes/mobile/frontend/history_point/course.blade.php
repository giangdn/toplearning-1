@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.accumulated_from_course'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="list-group list-group-flush">
                    @if(count($get_history_course) > 0)
                        @foreach($get_history_course as $item)
                            @php
                                if ($item->course_type == 1){
                                    $score = \Modules\Promotion\Entities\PromotionUserHistory::getHistoryPointCourseUser(Auth::id(), $item->id, 0);
                                    $type = 'Online';
                                }else{
                                    $score = \Modules\Promotion\Entities\PromotionUserHistory::getHistoryPointCourseUser(Auth::id(), $item->id, 1);
                                    $type = 'In house';
                                }
                            @endphp
                            <div class="row mb-1 bg-white shadow border">
                                <div class="col-auto align-self-center">
                                    <img src="{{ asset('themes/mobile/img/course_icon.png') }}" alt="" class="avatar-40">
                                </div>
                                <div class="col pl-0">
                                    <p class="mb-0">{{ $item->name }}</p>
                                    <p class="text-mute mt-1 mb-0">
                                        {{ get_date($item->start_date) }} @if($item->end_date) {{ ' - '. get_date($item->end_date) }} @endif
                                    </p>
                                    <p class="text-mute mt-1 mb-1">
                                        @if($score)
                                            {{ $score }} <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                                        @endif
                                        <span class="float-right">
                                            {{ $type }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                        <div class="row mt-2">
                            <div class="col-12 px-0 text-right">
                                {{ $get_history_course->links('themes/mobile/layouts/pagination') }}
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
