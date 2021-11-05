@extends('themes.mobile.layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <img src="{{ asset('themes/mobile/img/404.png') }}" class="avatar-100" alt="">
                    <h4>The page you were looking for could not be found.</h4>
                    <a href="/" class="btn btn-info text-white"><i class="material-icons">navigate_before</i> Go To Homepage</a>
                </div>
            </div>
        </div>
    </div>
@endsection
