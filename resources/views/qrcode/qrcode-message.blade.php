@extends('layouts.app')

@section('page_title', 'Thông báo')

@section('content')
<div class="container-fluid">
    @if (session('error'))
        <div class="alert alert-danger text-center" role="alert">
            <h2>{{ session('error') }}</h2>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success text-center" role="alert">
            <h2>{{ session('success') }}</h2>
        </div>
    @endif
</div>
@stop
