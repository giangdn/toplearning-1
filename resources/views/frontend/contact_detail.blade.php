@extends('layouts.app')

@section('page_title', 'Liên hệ')

@section('content')
    <div class="container-fluid guide-container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="ibox-content guide-container">
                    <h2 class="st_title"><i class="uil uil-apps">
                        </i><a href="{{ route('frontend.contact') }}" class="font-weight-bold">Liên hệ</a>
                    </h2>
                    <br>
                    <h2 class="mt-1">{{ $contact->name }}</h2>
                    <div>
                        <p>{!! $contact->description !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
