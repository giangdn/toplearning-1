@extends('layouts.app')

@section('page_title', trans('app.faq'))

@section('header')
    <style>
        #faq .card-body img{
            width: 100%;
        }
        #faq a{
            background: white;
            border-radius: 20px;
            border: 1px solid #8b1409;
            color: #333;
        }
        #faq a:hover{
            color: #fff !important;
            background: #8b1409;
        }

        #faq .title-faq{
            color: #8b1409;
        }
    </style>
@endsection

@section('content')
    <div class="container faq_body">
        <div id="faq">
            <div class="row">
                <div class="col-12 text-center m-2">
                    <h1 class="title-faq"><i class="far fa-comments"></i> {{ trans('app.faq') }}</h1>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 mt-2">
                    <form method="get" action="" id="form-search" class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Tìm kiếm', 'Search') }}" value="{{ request()->get('search') }}" onchange="submit();">
                    </form>
                </div>
                <div class="col-12 p-1">
                    @foreach($faqs as $faq)
                        <div class="card mb-1 border-0 shadow-sm">
                            <a href="" class="" data-toggle="collapse" data-target="#question{{ $faq->id }}" aria-expanded="true" aria-controls="question{{ $faq->id }}">
                                <div class="card-header py-2">
                                    {{ $faq->name }}
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
