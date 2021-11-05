@extends('layouts.app')

@section('page_title', 'Sách')

@section('header')
<link rel="stylesheet" href="{{ asset('styles/module/online/css/detail.css') }}">
<link rel="stylesheet" href="{{ asset('styles/module/online/css/list.css') }}">
<link rel="stylesheet" href="{{ asset('styles/module/libraries/css/styles-library.css') }}">
<script src="{{ asset('styles/module/libraries/js/frontend_lib.js') }}"></script>
@endsection

@section('content')
<div class="container-fluid">
    <div class="slider-top">
        @foreach($sliders as $slider)
            <img src="{{ image_file($slider->image) }}" style="width: 100%;max-height: 450px"/>
        @endforeach
    </div>
    <div class="content-main">
        <div class="br">
            <a href="/">{{ trans('app.home') }}</a> &raquo;
            <a href="{{ route('module.libraries') }}">{{ trans('app.libraries') }}</a> &raquo;
            <a href="{{ route('module.frontend.libraries.book',['id' => 0]) }}">{{ trans('app.book') }}</a> &raquo;
            <span>{{ $item->name }}</span>
        </div>

        <h4 class="title-top">
            <span class="red" style="text-transform: uppercase;">
                {{ $item->name }}
            </span>
        </h4>
        <div class="row row-info">
            <div class="col-md-3">
                <img src="{{ image_file($item->image) }}" alt="" style="width: 100%;"/>
            </div>
            <div class="col-md-6">
                <div class="item">
                    {{ trans('app.date_submit') .': '. get_date($item->created_at) }}
                </div>
                <div class="item">
                    {{ trans('app.num_register_book') .': '. $count_register }}
                </div>
                <div class="item">
                    {{ trans('app.posted_by') .': '. $created_by->lastname ." ". $created_by->firstname  }}
                </div>
                <div class="item">
                    {{ trans('app.num_books_remaining') .': '. $item->current_number }}
                </div>
                <div class="item">
                    <b>{{ trans('app.description') }}</b>
                    <p class="text-justify" style="overflow-y: auto; max-height: 279px; color: #000; padding-right: 5px;">{!! $item->description !!}</p>
                </div>

                <div class="item">{!! $status !!}</div>

                <div class="item">
                    <form action="{{ route('module.frontend.libraries.book.register', ['id' => $item->id]) }}" method="post" class="form-validate form-ajax" role="form" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        {{ trans('app.borrow_books') }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-lg-2">
                                        <input type="text" name="quantily" class="form-control is-number" min="1" placeholder="{{ trans('app.quantily') }}" value="" required>
                                    </div>
                                    <div class="col-md-10 col-sm-10 col-lg-10 form-inline">
                                        <input name="borrow_date" type="text" class="datepicker form-control" placeholder="{{ trans('app.borrow_date') }}" autocomplete="off" value="" required>
                                        <span class="fa fa-arrow-right" style="padding: 0 3px;"></span>
                                        <input name="pay_date" type="text" class="datepicker form-control" placeholder="{{ trans('app.pay_date') }}" autocomplete="off" value="" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p></p>
                        <button type="submit" class="btn-signup-ctkh" style="padding: 5px 20px;">{{ trans('app.register') }}</button>
                    </form>
                </div>
            </div>
            <div class="col-md-3 post-other">
                <h3>{{ trans('app.same_category') }}</h3>
                @foreach($categories as $category)
                    <div class="block-item">
                        <div class="image">
                            <img src="{{ image_file($category->image) }}" style="width: 100%;" class="img-responsive">
                        </div>
                        <div class="content m-2">
                            <div class="name"><a href="{{ route('module.libraries.book.detail', ['id' => $category->id]) }}">{{ $category->name }}</a></div>
                            <div class="description text-justify">{!! sub_char($category->description, 30)  !!} </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop
