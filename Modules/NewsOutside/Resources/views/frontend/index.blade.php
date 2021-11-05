@extends('layouts.app_outside')

@section('page_title', 'HỆ THỐNG QUẢN LÝ ĐÀO TẠO E-LEARNING')

@section('content')
    @php
        $date_now = date('Y-m-d H:s');
    @endphp
    <div class="banner_outside">
        <div class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($sliders as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <a href="{{ $slider->url }}">
                            <img src="{{ image_file($slider->image) }}" alt="" class="w-100" />
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container home-page-outside">
        <div class="body_outside row">
            <div class="col-12 cate_parent_name mt-3">
                <h4 class="mb-3">
                    <a href="{{ route('module.frontend.news_outside', ['cate_id' => 0, 'parent_id' => $cate_new_parent->id, 'type' => 0]) }}">
                        <span>{{$cate_new_parent->name}}</span>
                    </a>
                </h4>                
            </div>
            <div class="col-12">
                <div class="all_cate_news">
                    <ul>
                        @foreach ($all_cate_news as $all_cate_new)
                            @if (!empty($cate_new) && $all_cate_new->id == $cate_new->id && $type == 1)
                                <li class="access">
                                    <a href="{{ route('module.frontend.news_outside', ['cate_id' => $all_cate_new->id, 'parent_id' => $all_cate_new->parent_id, 'type' => 1]) }}">
                                        <span class="span_access"><strong>{{$all_cate_new->name}}</strong></span>
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('module.frontend.news_outside', ['cate_id' => $all_cate_new->id, 'parent_id' => $all_cate_new->parent_id, 'type' => 1]) }}">
                                        <span><strong>{{$all_cate_new->name}}</strong></span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="content_left col-md-8 col-12 mt-2">    
                <div class="row new_with_category mb-3">
                    <div class="col-12">
                        <div class="row mb-2 get_new">
                            @if (!empty($get_hot_new_of_category))
                            <div class="col-5 col-md-4 p-0">
                                <a href="{{ route('detail_home_outside',['id' => $get_hot_new_of_category->id, 'type' => $type]) }}">
                                    <img class="w-100" height="145px" src="{{ image_file($get_hot_new_of_category->image) }}" alt="" height="auto" style="object-fit: fill">
                                </a>
                            </div>
                            <div class="col-7 col-md-8 pt-2 hot_new_cate">
                                <div class="hot_new_title">
                                    <a href="{{ route('detail_home_outside',['id' => $get_hot_new_of_category->id, 'type' => $type]) }}">
                                        <h6 class="mb-2">{{ $get_hot_new_of_category->title }}
                                            @if ($date_now < $get_hot_new_of_category->date_setup_icon)
                                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                            @endif
                                        </h6>
                                    </a>
                                </div>
                                <div class="created_at mb-2">
                                    {{ \Carbon\Carbon::parse($get_hot_new_of_category->created_at)->diffForHumans() }}
                                </div>
                                <div class="hot_new_description">
                                    {{ $get_hot_new_of_category->description }}
                                </div>                                    
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @if (!empty($get_hot_new_of_category) && !empty($get_related_news_hot_outside) )
                    <div class="col-12 related_news_hot_cate mt-2">
                        <div class="row m-0">
                            @foreach ($get_related_news_hot_outside as $get_related_new_hot_outside)
                                <div class="col-4 related_new_hot px-2">
                                    <a class="link_related_new_hot" href="{{ route('detail_home_outside',['id' => $get_related_new_hot_outside->id, 'type' => $type]) }}">
                                        <h6 class="title_related_new_hot">{{ $get_related_new_hot_outside->title }}
                                            @if ($date_now < $get_related_new_hot_outside->date_setup_icon)
                                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                            @endif
                                        </h6>
                                        <div class="show_all_title_related_new_hot">
                                            {{ $get_related_new_hot_outside->title }}
                                            @if ($date_now < $get_related_new_hot_outside->date_setup_icon)
                                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                            @endif
                                        </div>
                                    </a>
                                    <div class="hot_new_description">
                                        {{ $get_related_new_hot_outside->description }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>      

                <div class="mt-2 news_category">
                    @livewire('newsoutside.news', ['type' => $type, 'category_id' => $cate_id, 'parent_id' => $parent_id, 'get_id_cate_parent_related' => $get_id_cate_parent_related])
                </div>
            </div>

            <div class="content_right col-md-4 col-12">
                @include('frontend.news_outside_right')
                @include('frontend.exchange_rate')
            </div>
        </div>
        @include('layouts.footer_outside')
    </div>
<script>
    $(window).on('load', function () {
        $('html,body').animate({scrollTop: 290}, 900, 'swing');
    });
    $(window).scroll(function() { 
        if($(window).scrollTop() + $(window).height() >= $(document).height()) { 
            window.livewire.emit('load-more'); 
        }
    }); 
</script>
@endsection
