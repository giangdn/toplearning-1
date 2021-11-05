@extends('layouts.app')

@section('page_title', 'Thư viện')

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
                                        <span>@lang('app.libraries')</span>
                                        <i class="uil uil-angle-right"></i>
                                        <a href="{{route('module.frontend.libraries.document',['id' => 0])}}">
                                            <span class="font-weight-bold">@lang('app.document')</span>
                                        </a>
                                        @if (!empty($all_name_cate_document))
                                            @foreach($all_name_cate_document as $key => $name_cate_document)
                                                <i class="uil uil-angle-right"></i>
                                                <a href="{{route('module.frontend.libraries.document',['id' => $name_cate_document->id])}}">
                                                    <span class="font-weight-bold">{{ $name_cate_document->name }}</span>
                                                </a>
                                            @endforeach
                                        @endif
                                    </h2>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row search-course pb-2">
                                    <div class="col-md-12 mt-3 form-inline">
                                        <form action="{{route('module.frontend.libraries.document',['id' => 0])}}" method="POST" class="form-inline w-100" id="form-search">
                                            {{ csrf_field() }}
                                            <div class="col-12 col-md-3 pl-0">
                                                <select name="search_cate" class="form-control search_type w-100"> 
                                                    <option value="" selected disabled>Danh mục</option>
                                                    @foreach ($get_categorys_document as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-2 pl-0">
                                                <div class="libraries_name">
                                                    <input type="text" name="search" id="search" value="" class="form-control search_text w-100" placeholder="Tên tài liệu" style="width: 230px">
                                                    <div class="name_libraries" id="name_libraries" style="display: none;">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-2 pl-0">
                                                <div class="authors pr-1">
                                                    <input type="text" name="search_author" id="search_author" class="form-control search_text w-100" placeholder="--Tên tác giả--">
                                                    <div class="name_author" id="name_author" style="display: none;">
                                        
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-md-2 pl-0">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{ trans('backend.search') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="_14d25 mb-5">
                                    @if ($set_paginate == 1)
                                        <div class="row m-0">
                                            @foreach($documents as $item)
                                                @include('libraries::frontend.item')
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
    <script>
        $('#search').keyup(function() {
            var value = $(this).val();
            if (value.length > 1) {
                $('#name_libraries').css('display','block')
            } else {
                $('#name_libraries').css('display','none')
            }
            $.ajax({
                type: 'post',
                url:  '{{ route('module.libraries.book.get_name_libraries') }}',
                data:{
                    search_libraries: value,
                    type: 3,
                },
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        $('#name_libraries p').remove();
                        $.each(data, function (index,item){
                            $('.name_libraries').append(`<p onclick="addNameLibraries('`+item.name+`')">`+item.name+`</p>`)
                        });
                    } 
                }
            });
        });
        function addNameLibraries(code) {
            $('#search').val(code);
            $('#name_libraries').css('display','none')
        }

        $('#search_author').keyup(function() {
            var value = $(this).val();
            if (value.length > 1) {
                $('#name_author').css('display','block')
            } else {
                $('#name_author').css('display','none')
            }
            $.ajax({
                type: 'post',
                url:  '{{ route('module.libraries.book.get_author') }}',
                data:{
                    search_author: value,
                    type: 3,
                },
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        $('#name_author p').remove();
                        $.each(data, function (index,item){
                            $('.name_author').append(`<p onclick="addNameAuthor('`+item.name_author+`')">`+item.name_author+`</p>`)
                        });
                    } 
                }
            });
        });
        function addNameAuthor(code) {
            $('#search_author').val(code);
            $('#name_author').css('display','none')
        }

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
                url: '{{ route('module.frontend.libraries.document',['id' => 0]) }}' + "?page=" + page,
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
        
        function hoverRatting(id, i, ratting) {
            if(ratting == 0) {
                $('.rating_star_item_' + id).each(function(e){
                    if (e < i) {
                        $(this).addClass('full-star');
                    } else {
                        $(this).removeClass('full-star');
                    }
                });
            }
        }

        function outRatting(id, i, ratting) {
            if (ratting == 0) {
                $('.rating_star_item_' + id).each(function(e){
                    $(this).removeClass('full-star');
                });
            } 
        }

        function ratting(id,i, ratting) {
            $.ajax({
            url: "{{ route('module.frontend.ratting') }}",
            type: 'post',
            data: {
                'star':i,
                'id' : id
            },
            }).done(function(data) {    
                show_message(data.message, data.status);
                setTimeout(function () {
                    window.location = '';    
                }, 2000);    
                return false;
            }).fail(function(data) {
                show_message('{{ trans('lageneral.data_error ') }}', 'error');
                return false;
            });
        }
    </script>
@stop
