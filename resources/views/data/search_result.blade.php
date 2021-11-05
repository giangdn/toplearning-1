@extends('layouts.app')

@section('page_title', trans('backend.offline_course'))

@section('header')

@endsection

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="st_title"><i class="uil uil-apps"></i><a href="{{ route('frontend.home') }}">@lang('app.home')</a>
                                    <i class="uil uil-angle-right"></i>
                                    <span class="font-weight-bold">@lang('app.result')</span>
                                </h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="_14d25">
                                    <div class="row">
                                        @if ($items->count() > 0)
                                            @foreach($items as $item)
                                                <div class="col-lg-3 col-md-4">
                                                    @include('data.course_item',['type'=>$item->type])
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="fcrse_1 mb-20">
                                                <div class="text-center">
                                                    <span>@lang('app.not_found')</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
