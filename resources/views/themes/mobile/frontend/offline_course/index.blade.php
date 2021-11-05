@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.in_house'))

@section('content')
    <div class="container">
        @if(count($items) > 0)
            <div class="row">
            @foreach($items as $offline)
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1">
                    <div class="card shadow border-0 mb-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 p-1">
                                <img src="{{ image_file($offline->image) }}" alt="" class="w-100 picture_course">
                            </div>
                            <div class="col-12 p-1 align-self-center">
                                <a href="{{ route('themes.mobile.frontend.offline.detail', ['course_id' => $offline->id]) }}">
                                    <h6 class="mb-2 mt-1 font-weight-normal">{{ $offline->name }}</h6>
                                </a>
                                <p class="text-mute">
                                    <b>@lang('app.time'): </b> {{ get_date($offline->start_date) }} @if($offline->end_date) {{ ' - '. get_date($offline->end_date) }} @endif
                                    <br>
                                    <b>@lang('app.register_deadline'):</b> {{ get_date($offline->register_deadline) }}
                                    <span class="float-right">In house</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            @endforeach
            </div>
            <div class="row">
                <div class="col-6">
                    @if($items->previousPageUrl())
                    <a href="{{ $items->previousPageUrl() }}" class="bp_left">
                        <i class="material-icons">navigate_before</i> @lang('app.previous')
                    </a>
                    @endif
                </div>
                <div class="col-6 text-right">
                    @if($items->nextPageUrl())
                    <a href="{{ $items->nextPageUrl() }}" class="bp_right">
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
