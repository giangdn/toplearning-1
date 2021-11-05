@extends('layouts.app')

@section('page_title', trans('backend.training_video'))

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    <h2 class="st_title"><i class="uil uil-apps"></i>
                                        <span class="font-weight-bold">@lang('backend.training_video')</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="explore_search">
                                    <div class="row">
                                        <div class="col-8">
                                            <form method="get" action="" id="form-search">
                                                <div class="row">
                                                    <div class="col-12 col-md-4 pr-0">
                                                        <input type="text" name="search" class="form-control" placeholder="{{ data_locale('Nhập tên video hoặc hashtag', 'Enter video name or hashtag') }}">
                                                    </div>
                                                    <div class="col-12 col-md-4 pr-0">
                                                        <select name="category" id="" class="form-control select2">
                                                            <option value="" disabled selected>Chọn danh mục</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3 input-group-btn">
                                                        <button id="trans_button" class="btn btn-info" type="submit"><i class="uil uil-search"></i> {{ trans('app.search') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-4">
                                            @if ($type == 1)
                                                <a href="{{ route('module.daily_training.frontend') }}" class="btn btn-info position-relative add_video" data-turbolinks="false">
                                                    Tất cả video
                                                </a>
                                            @else
                                                <a href="{{ route('module.daily_training.frontend.my_video') }}" class="btn btn-info position-relative add_video" data-turbolinks="false">
                                                    Video của tôi
                                                </a>
                                            @endif
                                            <a href="{{ route('module.daily_training.frontend.add_video') }}" class="btn btn-info position-relative add_video" data-turbolinks="false">
                                                <i class="uil uil-plus-circle"></i> @lang('app.add_video')
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="_14d25">
                                    @if ($set_paginate == 1)
                                        <div class="row">
                                            @foreach($videos as $video)
                                                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 p-1">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <a href="{{ route('module.daily_training.frontend.detail_video', ['id' => $video->id]) }}">
                                                                <img src="{{ image_file($video->avatar) }}" alt="" class="w-100" style="height: 250px;">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row mx-0 mb-4 mt-1">
                                                        <div class="col-3 avatar_account_daily p-1">
                                                            <img src="{{ \App\Profile::avatar($video->created_by) }}" alt="" class="ml-0 w-100">
                                                        </div>
                                                        <div class="{{ \Auth::id() == $video->created_by ? 'col-7' : 'col-8' }} pl-1 pr-0">
                                                            <a href="{{ route('module.daily_training.frontend.detail_video', ['id' => $video->id]) }}" class="crse14s link_daily_training">
                                                                <span class="daily_name_training">{{ $video->name }}</span>
                                                                <div class="show_daily_name_training">
                                                                    {{ $video->name }}
                                                                </div>
                                                            </a>
                                                            <p class="text-mute small mb-1">{{ \App\Profile::fullname($video->created_by) .' - '. $video->view .' '. trans('app.view')}}</p>
                                                            <p class="text-mute small mb-1">{{ \Carbon\Carbon::parse($video->created_at)->diffForHumans()}}</p>
                                                        </div>
                                                        @if(\Auth::id() == $video->created_by)
                                                            <div class="col-2 p-0">
                                                                <span class="text-danger pr-2">
                                                                    <img src="{{ asset('themes/mobile/img/heart.png') }}" alt="" style="width: 15px; height: 15px;">
                                                                </span>
                                                                <div class="eps_dots more_dropdown">
                                                                    <a href="javascript:void(0)"><i class="uil uil-ellipsis-v"></i></a>
                                                                    <div class="dropdown-content">
                                                                        <span class="disable-video text-danger" data-video_id="{{ $video->id }}">
                                                                        <i class="uil uil-ban"></i> @lang('app.delete')
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col-1 p-0">
                                                                <div class="float-right">
                                                                    <span class="text-danger pr-2">
                                                                        <img src="{{ asset('themes/mobile/img/heart.png') }}" alt="" style="width: 15px; height: 15px;">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        @endif
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
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function deleteVideo(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('module.daily_training.frontend.disable_video') }}',
                dataType: 'json',
                data: {
                    'id': id
                }
            }).done(function(data) {
                window.location = '';
                return false;
            }).fail(function(data) {
                return false;
            });
        }

        var page = 1; 
        load_more(page); 
        $(window).scroll(function() { 
            if($(window).scrollTop() + $(window).height() >= $(document).height()) { 
                page++; 
                load_more(page);   
            }
        });     
        var type = '{{ $type }}';
        if (type == 0) {
            var ajax_url = "{{ route('module.daily_training.frontend') }}?page=";
        } else {
            var ajax_url = "{{ route('module.daily_training.frontend.my_video') }}?page=";
        }
        function load_more(page){
            $.ajax({
                url: ajax_url + page,
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
