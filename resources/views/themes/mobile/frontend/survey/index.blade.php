@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.survey'))

@section('content')
    <div class="container">
        @if(count($surveys) > 0 && (\App\Profile::usertype() != 2))
            @foreach($surveys as $item)
                @php
                    $survey = $item->users->where('id',auth()->id())->first();
                    $format_endate = \Carbon\Carbon::parse($item->end_date)->format('Y-m-d');
                    $date = date('Y-m-d');
                @endphp
                <div class="row bg-white mb-2">
                    <div class="col-12">
                        <h6 class="mt-1 font-weight-bold">{{ $item->name }}</h6>
                    </div>
                    <div class="col-5 text-center pr-0">
                        <img alt="{{ $item->name }}" class="lazy w-100" src="{{ $item->image ? image_file($item->image) : asset('themes/mobile/img/survey.png') }}" height="150px" style="object-fit: cover">
                    </div>
                    <div class="col-7 align-self-center">
                        <p style="font-size: 85%">
                            <b>@lang('app.time') : </b>
                            <strong>{{ \Carbon\Carbon::parse($item->start_date)->format('H:i d/m/Y') }}</strong>
                            <br>
                            <b>@lang('app.end') : </b>
                            <strong>{{ \Carbon\Carbon::parse($item->end_date)->format('H:i d/m/Y') }}</strong>
                            <br>
                            @if($survey && $survey->pivot->send == 1)
                                <b>@lang('app.status') : </b> @lang('app.completed')
                            @endif

                            <span class="float-left">
                                @if(\Carbon\Carbon::now()->diffInHours($item->start_date) <= 3 && \Carbon\Carbon::now()->diffInHours($item->start_date) > 0)
                                    @lang('app.upcoming')
                                @elseif($item->start_date > date('Y-m-d H:i:s'))
                                @elseif (!$survey && ($date > $format_endate))
                                    <button type="button" class="btn btn-danger float-right">@lang('app.end_survey')</button>
                                @elseif (!$survey && ($item->start_date <= date('Y-m-d H:i:s')))
                                    <a href="{{ route('module.survey.user', ['id' => $item->id]) }}" class="btn btn-info text-white">
                                        @lang('app.take_survey')
                                    </a>
                                @elseif ($survey && $survey->pivot->send == 1)
                                    <a href="{{ route('module.survey.user.edit', ['id' => $item->id]) }}" class="btn btn-info text-white">
                                       @lang('app.view_survey')
                                    </a>
                                @else
                                    <a href="{{ route('module.survey.user.edit', ['id' => $item->id]) }}" class="btn btn-info text-white">
                                        @lang('app.edit_survey')
                                    </a>
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            @endforeach
            <div class="row">
                <div class="col-6">
                    @if($surveys->previousPageUrl())
                        <a href="{{ $surveys->previousPageUrl() }}" class="bp_left">
                            <i class="material-icons">navigate_before</i> @lang('app.previous')
                        </a>
                    @endif
                </div>
                <div class="col-6 text-right">
                    @if($surveys->nextPageUrl())
                        <a href="{{ $surveys->nextPageUrl() }}" class="bp_right">
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
