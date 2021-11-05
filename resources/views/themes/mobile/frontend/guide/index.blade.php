@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.guide'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col bg-white">
                @if (count($guides) > 0)
                <table class="tDefault table table-hover table-bordered">
                    <thead>
                        <th class="text-center pl-1 pr-1" width="3%">@lang('app.stt')</th>
                        <th>@lang('app.info')</th>
                    </thead>
                    <tbody>
                        @foreach ($guides as $key => $guide)
                            <tr>
                                <td class="text-center pl-1 pr-1">{{ $key + 1 }}</td>
                                <td>
                                    {{ $guide->name }} <br>
                                    <a href="{{ route('frontend.guide.view_pdf', ['id' => $guide->id]) }}" class="btn btn-warning p-1">
                                        <span class="small"> <i class="material-icons vm small">remove_red_eye</i> @lang('app.watch_online')</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
@endsection
