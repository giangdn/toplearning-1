@extends('layouts.app')

@section('page_title', 'Quà tặng')

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-8">
                    <div class="section3125 pt-3">
                        <div class="ibox-content forum-container">
                            <h2 class="st_title"><i class="uil uil-apps"></i><span class="font-weight-bold">@lang('app.promotion')</span></h2>
                        </div>
                        <div class="explore_search">
                            <form method="get" class="">
                                <div class="ui search focus">
                                    <div class="ui left icon input swdh11">
                                        <input id="gift" class="prompt srch_explore" type="text" name="search" placeholder="@lang('app.group_gift')" value="{{ request()->get('search') }}">
                                        <i class="uil uil-search-alt icon icon2"></i>
                                        <select class="prompt srch_explore" name="sort_score" onchange="submit();">
                                            <option value="" readonly>@lang('app.gift_point_filter')</option>
                                            <option value="1">@lang('app.ascending')</option>
                                            <option value="2">@lang('app.decrease')</option>
                                        </select>
                                        <button id="button_promo" type="submit" class="btn btn-info">@lang('app.search')</button>
                                    </div>
                                </div>
                                <button id="button_mobile" type="submit" class="btn btn-info">@lang('app.search')</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="_14d25 mt-0">
                        @if ($set_paginate == 1)
                            <div class="row m-0">
                                @foreach ($promotions as $promotion)                                
                                    <div class="col-xl-3 col-lg-4 col-md-6 p-1">
                                        <div class="fcrse_1 mt-2">
                                            <div class="promotion-images">
                                                <a href="#"><img src="{{ image_file($promotion->images) }}" alt=""></a>
                                            </div>
                                            <div class="tutor_content_dt">
                                                <div class="tutor150">
                                                    <a href="#" class="tutor_name">{{ $promotion->name }}</a>
                                                    <div class="mef78" title="Verify">
                                                        <i class="uil uil-check-circle"></i>
                                                    </div>
                                                </div>
                                                <div class="tutor_cate"><a href="#">{{ $promotion->group_name }}</a></div>
                                                <form action="{{ route('module.front.promotion.get', ['id' => $promotion->id]) }}" method="post" class="form-ajax">
                                                    @csrf
                                                    <button type="submit" class="btn btn_adcart btn-promotion">
                                                        {{ $promotion->point }}
                                                        <img class="point w-5" src="{{ asset('images/level/point.png') }}" alt="">
                                                    </button>
                                                </form>

                                                <div class="tut1250">
                                                    <span class="vdt15"><strong>@lang('app.quantity') : {{ $promotion->amount }}</strong></span>
                                                    <span class="vdt15"><strong>@lang('app.period') : {{ \Carbon\Carbon::parse($promotion->period)->format('d/m/Y') }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="row m-0" id="results"></div>
                            <div class="ajax-loading text-center mb-5">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div> 
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var page = 1; 
        load_more(page); 
        $(window).scroll(function() { 
            if($(window).scrollTop() + $(window).height() >= $(document).height()) { 
                page++; 
                load_more(page);   
            }
        });     
        function load_more(page){
            $.ajax({
                url: '{{ route('module.front.promotion') }}' + "?page=" + page,
                type: "get",
                datatype: "html",
                beforeSend: function() 
                {
                    $('.ajax-loading').show();
                }
                })
                .done(function(data)
                {
                    if(data.length == 0){
                    console.log(data.length);
                    $('.ajax-loading').html("No more records!");
                    return;
                }
                $('.ajax-loading').hide(); 
                $("#results").append(data);        
                console.log('data.length');
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                alert('No response from server');
            });
        }
    </script>
@endsection
