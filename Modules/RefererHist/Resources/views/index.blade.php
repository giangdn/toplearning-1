@extends('refererhist::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('refererhist.name') !!}
    </p>
@endsection
