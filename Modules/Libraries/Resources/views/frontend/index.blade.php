@extends('layouts.app')

@section('page_title', 'Thư viện')

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="st_title library_title"><i class="uil uil-apps"></i>
                                    <a href="{{ route('module.frontend.libraries.book',['id' => 0]) }}">@lang('app.book')</a>
                                </h1>
                            </div>
                            <div class="col-md-12">
                                <div class="_14d25">
                                    <div class="row">
                                        @foreach($books as $item)
                                            @include('libraries::frontend.item')
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="st_title library_title"><i class="uil uil-apps"></i>
                                    <a href="{{ route('module.frontend.libraries.ebook',['id' => 0]) }}">@lang('app.ebook')</a>
                                </h1>
                            </div>
                            <div class="col-md-12">
                                <div class="_14d25">
                                    <div class="row">
                                        @foreach($ebooks as $item)
                                            @include('libraries::frontend.item')
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="st_title library_title"><i class="uil uil-apps"></i>
                                    <a href="{{ route('module.frontend.libraries.document',['id' => 0]) }}">@lang('app.document')</a>
                                </h1>
                            </div>
                            <div class="col-md-12">
                                <div class="_14d25">
                                    <div class="row">
                                        @foreach($documents as $item)
                                            @include('libraries::frontend.item')
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="st_title library_title"><i class="uil uil-apps"></i>
                                    <a href="{{ route('module.frontend.libraries.video',['id' => 0]) }}">Video</a>
                                </h1>
                            </div>
                            <div class="col-md-12">
                                <div class="_14d25">
                                    <div class="row">
                                        @foreach($video as $item)
                                            @include('libraries::frontend.item')
                                        @endforeach
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
