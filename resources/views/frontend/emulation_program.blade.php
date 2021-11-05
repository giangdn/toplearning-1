@extends('layouts.app')

@section('page_title', 'Chương trình thi đua')

@section('content')
<div class="sa4d25">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content forum-container">
                    <h2 class="st_title"><i class="uil uil-apps"></i>
                        <span class="font-weight-bold">Chương trình thi đua</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row">
            @if (!$banners->isEmpty())
                <div class="col-12 my-3">
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($banners as $key => $banner)
                                <div class="carousel-item {{ $key == 0 ? 'active' : ''}}">
                                    <a href="{{$banner->url}}">
                                        <img class="d-block w-100" src="{{image_file($banner->image)}}"  width="100%" height="350px" style="object-fit: fill;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div class="row search-course mt-3 pb-2">
            <div class="col-12 form-inline">
                <form action="" method="get" class="form-inline" id="form-search">
                    {{ csrf_field() }}
                    <input type="text" name="search" value="" class="form-control search_text mr-1" placeholder="Nhập Mã/Tên chương trình">
                    <input name="start_date" type="text" class="datepicker form-control search_start_date mr-1" placeholder="{{trans('backend.start_date')}}" autocomplete="off">
                    <input name="end_date" type="text" class="datepicker form-control search_end_date mr-1" placeholder="{{trans('backend.end_date')}}" autocomplete="off">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if (!empty($items))
                    @if ($set_paginate == 1)
                        <div class="row m-0">
                            @foreach($items as $item)
                                <div class="col-lg-3 col-md-4 p-1">
                                    @include('data.emulation_item')
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
                @else
                <div class="row">
                    <div class="fcrse_1 mb-20">
                        <div class="text-center">
                            <span>@lang('app.not_found')</span>
                        </div>
                    </div>
                </div>
                @endif
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
            url: '{{ route('frontend.emulation_program') }}' + "?page=" + page,
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
@stop

