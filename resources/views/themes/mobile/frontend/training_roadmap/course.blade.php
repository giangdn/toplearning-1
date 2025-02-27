@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.course_roadmap'))

@section('content')
    <div class="container">
        @if(count($training_roadmap_course) > 0)
            <div class="row">
                @foreach($training_roadmap_course as $item)
                    @if($item->id)
                        @php
                            if ($item->course_type == 1){
                                $result = \Modules\Online\Entities\OnlineCourse::checkCompleteCourse($item->id, \Auth::id());
                                $percent = \Modules\Online\Entities\OnlineCourse::percentCompleteCourseByUser($item->id, \Auth::id());
                                $isRating = \Modules\Online\Entities\OnlineRating::getRating($item->id, \Auth::id());
                                $route = route('themes.mobile.frontend.online.detail', ['course_id' => $item->id]);
                                $type = 'Online';
                            }else{
                                $result = \Modules\Offline\Entities\OfflineCourse::checkCompleteCourse($item->id, \Auth::id());
                                $percent = \Modules\Offline\Entities\OfflineCourse::percent($item->id, \Auth::id());
                                $isRating = \Modules\Offline\Entities\OfflineRating::getRating($item->id, \Auth::id());
                                $route = route('themes.mobile.frontend.offline.detail', ['course_id' => $item->id]);
                                $type = 'In house';
                            }
                        @endphp
                        <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1">
                        <div class="card shadow border-0 mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 p-1">
                                        <a href="{{ $route }}">
                                            <img src="{{ image_file($item->image) }}" alt="" class="mw-100">
                                        </a>
                                    </div>
                                    <div class="col-12 p-1 align-self-center">
                                        <a href="{{ $route }}">
                                            <h6 class="mb-2 mt-1 font-weight-normal">{{ $item->name }}</h6>
                                        </a>
                                        <div class="rating-box">
                                            @for ($i = 1; $i < 6; $i++)
                                                <span class="rating-star @if(!$isRating) empty-star rating @elseif($isRating && $isRating->num_star >= $i) full-star @endif" data-value="{{ $i }}"></span>
                                            @endfor
                                        </div>
                                        <p class="text-mute">
                                            <b>@lang('app.time'): </b> {{ get_date($item->start_date) }} @if($item->end_date) {{' - '. get_date($item->end_date) }} @endif
                                            <br>
                                            <b>@lang('app.status'):</b> {{ $result ? 'Hoàn thành' : 'Chưa hoàn thành' }}
                                            <span class="float-right">{{ $type }}</span>
                                        </p>
                                        <div class="progress progress2">
                                            <div class="progress-bar w-70" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($percent, 2) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            <div class="row">
                <div class="col-6">
                    @if($training_roadmap_course->previousPageUrl())
                    <a href="{{ $training_roadmap_course->previousPageUrl() }}" class="bp_left">
                        <i class="material-icons">navigate_before</i> @lang('app.previous')
                    </a>
                    @endif
                </div>
                <div class="col-6 text-right">
                    @if($training_roadmap_course->nextPageUrl())
                    <a href="{{ $training_roadmap_course->nextPageUrl() }}" class="bp_right">
                        @lang('app.next') <i class="material-icons">navigate_next</i>
                    </a>
                    @endif
                </div>
            </div>
        @else
            <div class="row">
                <div class="col text-center">
                    <span>@lang('app.not_found')</span>
                </div>
            </div>
        @endif
    </div>
@endsection
