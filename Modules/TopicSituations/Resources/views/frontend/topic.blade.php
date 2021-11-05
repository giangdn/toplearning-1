@extends('layouts.app')

@section('page_title', 'Xử lý tình huống')

@section('content')
<div class="sa4d25">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content forum-container">
                    <h2 class="st_title">
                        <i class="uil uil-apps"></i>
                        <span class="font-weight-bold">Xử lý tình huống</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row search-course pb-2 my-2">
            <div class="col-12 form-inline">
                <form action="{{ route('frontend.topic_situations') }}" method="get" class="form-inline w-100" id="form-search">
                    {{ csrf_field() }}
                    <input type="text" name="search" value="" class="form-control search_text mr-1" placeholder="Nhập Mã/Tên tình huống">
                    <input name="start_date" type="text" class="datepicker form-control search_start_date mr-1" placeholder="{{trans('backend.start_date')}}" autocomplete="off">
                    <input name="end_date" type="text" class="datepicker form-control search_end_date mr-1" placeholder="{{trans('backend.end_date')}}" autocomplete="off">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if ($topics->count() > 0)
                    @if ($set_paginate == 1)
                        <div class="row m-0 all_topic">
                            <div class="col-12 p-0">
                                <div class="row m-0">
                                    @foreach($topics as $topic)
                                        @php
                                            $topic_created_at = \Carbon\Carbon::parse($topic->created_at)->format('d/m/Y');
                                        @endphp
                                        <div class="col-lg-3 col-md-4 p-1">
                                            <div class="fcrse_1 my-3 p-0">
                                                <a href="{{route('frontend.get.situations',['id' => $topic->id])}}" class="image_topic_link">
                                                    <img class="picture_topic" src="{{ image_file($topic->image) }}" alt="" height="150px">
                                                </a>
                                                <div class="fcrse_content px-3">
                                                    <div class="course_names text-break">
                                                        <a href="{{route('frontend.get.situations',['id' => $topic->id])}}" class="crse14s topic_name">{{ $topic->name }}</a>
                                                        <p class="">Ngày tạo: {{ $topic_created_at }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row m-0 all_topic" id="results"></div>
                        <div class="ajax-loading text-center mb-5">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div> 
                    @endif
                @else
                    <div class="row m-0 all_topic">
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
                url: '{{ route('frontend.topic_situations') }}' + "?page=" + page,
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
