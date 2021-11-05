@extends('layouts.app')
@section('page_title', 'Đăng nhập')

@section('header')
    <link href="{{ asset('styles/css/frontend/styles-login.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Bạn đã đăng nhập tài khoản này ở nơi khác <br> Bạn cần đăng xuất trước khi đăng nhập lại</h1>
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="1">
                        <input type="hidden" name="user_id" value="{{$user_id}}">
                        <button type="submit" class="btn btn-info btn-login">Đồng ý</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="0">
                        <input type="hidden" name="user_id" value="{{$user_id}}">
                        <button type="submit" class="btn btn-ghost btn-login">Không đồng ý</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <p></p>
</div>
@endsection
