@extends('layouts.app')

@section('page_title', trans('app.notify'))

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i>
                                        <a href="{{ route('module.notify.index') }}"><span class="font-weight-bold">@lang('app.notify')</span></a>
                                    </h2>
                                </div>
                            </div>
                        </div>
                       <p></p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-12">
                                            <div class="news-header">
                                                <h2 class="st_title">{{ $notify->subject }}</h2>
                                                <span class="news-time">{{ \Carbon\Carbon::parse($notify->created_at)->format('H:s d/m/Y') }}</span>
                                            </div>
                                            <div class="news-content text-justify">
                                                {!! $notify->content !!}
                                            </div>
                                        </div>
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
