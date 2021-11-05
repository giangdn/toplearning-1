@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.notify'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white border-bottom">
                        <h6>{{ $notify->subject }}</h6>
                        <p>{{ get_date($notify->created_at) }}</p>
                    </div>
                    <div class="card-body">
                        {!! $notify->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
