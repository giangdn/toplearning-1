@extends('layouts.app')

@section('page_title', 'Hướng dẫn')

@section('content')
    <div class="container-fluid guide-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content guide-container">
                    <h2 class="st_title"><i class="uil uil-apps">
                        </i><a href="{{ route('module.frontend.guide.posts') }}" class="font-weight-bold">Hướng dẫn bài viết</a>
                    </h2>
                    <br>
                    <h2 class="mt-1">{{ $guide->name }}</h2>
                    <div>
                        <p>{!! $guide->attach !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
