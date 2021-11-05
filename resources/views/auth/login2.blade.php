@extends('layouts.app')

@section('page_title', 'Đăng nhập')

@section('header')
    <link href="{{ asset('styles/css/frontend/styles-login.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

@php
    $img = \App\Slider::where('status', '=', 1)->latest()->first();
@endphp

<div class="container-fluid" id="content-login" style="background: url({{ $img ? image_file($img->image) : asset('/styles/images/img-login.jpg')}}) no-repeat; background-size:100%;">
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-10">
                    <div class="row row-login">
                        <div class="col-md-12">
                            <div class="header ">
                                <h1>Đăng nhập</h1>
                            </div>
                        </div>
                    </div>

                    <div class="row row-login">
                        <div class="col-md-12">
                            <div class="login-form-wrap">
                                @error('login_error')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                <form action="{{ route('login') }}" method="POST" autocomplete="off">
                                    @csrf
                                    <input type="text" name="username" class="login-input" placeholder="Tên tài khoản" required>

                                    <input type="password" name="password" class="login-input" placeholder="{{trans('backend.pass')}}" required>

                                    <button type="submit" class="btn btn-ghost purple btn-login">Đăng nhập</button>

                                </form>
                            </div>
                            <div class="login-sub-btn">
                                <a href=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
