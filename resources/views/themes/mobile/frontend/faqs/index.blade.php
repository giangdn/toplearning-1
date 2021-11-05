@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.faq'))

@section('header')
    <style>
        #faq .card-body img{
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div id="faq">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 mt-2">
                    <form method="get" action="" id="form-search" class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Tìm kiếm', 'Search') }}" value="{{ request()->get('search') }}" onchange="submit();">
                    </form>
                </div>
                <div class="col-12 p-1">
                    @foreach($faqs as $faq)
                        <div class="card mb-1 border-0 shadow-sm">
                            <a href="" class="text-white" data-toggle="collapse" data-target="#question{{ $faq->id }}" aria-expanded="true" aria-controls="question{{ $faq->id }}">
                                <div class="card-header bg-primary py-2 title_question_faq row">
                                    <div class="col-10">
                                        <span>{{ $faq->name }}</span>
                                    </div>
                                    <div class="col-2">
                                        <img src="{{ asset('images/caret-down.png') }}" alt="" class="float-right">
                                    </div>
                                </div>
                                
                            </a>
                            <div id="question{{ $faq->id }}" class="collapse" data-parent="#faq">
                                <div class="card-body text-justify">
                                    {!! $faq->content !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
