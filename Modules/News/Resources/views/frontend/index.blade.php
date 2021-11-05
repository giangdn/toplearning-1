@extends('layouts.app')

@section('page_title', trans('app.news'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/menu_new_inside.css') }}">
    @php
        $date_now = date('Y-m-d H:s');
    @endphp
    <div class="sa4d25">
        <div class="container-fluid">
            @include('news::frontend.menu_new')
            <div class="body_news row my-3">
                <div class="col-12 cate_parent_name">
                    <h3 class="mb-3">
                        <a href="{{ route('module.news.cate_new', ['parent_id' => $cate_new_parent->id, 'id' => 0, 'type' => 0]) }}">
                            <span>{{$cate_new_parent->name}}</span>
                        </a>

                    </h3>
                </div>
                <div class="col-12">
                    <div class="all_cate_news">
                        <ul>
                            @foreach ($all_cate_news_name as $all_cate_new_name)
                                @if (!empty($cate_new) && $all_cate_new_name->id == $cate_new->id && $type == 1)
                                    <li class="access">
                                        <a href="{{ route('module.news.cate_new', ['parent_id' => $all_cate_new_name->parent_id, 'id' => $all_cate_new_name->id, 'type' => 1]) }}">
                                            <span class="span_access"><strong>{{$all_cate_new_name->name}}</strong></span>
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('module.news.cate_new', ['parent_id' => $all_cate_new_name->parent_id, 'id' => $all_cate_new_name->id, 'type' => 1]) }}">
                                            <span><strong>{{$all_cate_new_name->name}}</strong></span>
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
                                <div class="col-5 p-0">
                                    <a href="{{ route('module.news.detail',['id' => $get_hot_new_of_category->id]) }}">
                                        <img class="w-100" height="145px" src="{{ image_file($get_hot_new_of_category->image) }}" height="auto" alt="" style="object-fit: fill">
                                    </a>
                                </div>
                                <div class="col-7 pt-2 hot_new">
                                    <div class="hot_new_title">
                                        <a href="{{ route('module.news.detail',['id' => $get_hot_new_of_category->id]) }}">
                                            <h4 class="mb-2"><strong>{{ $get_hot_new_of_category->title }}</strong>
                                                @if ($date_now < $get_hot_new_of_category->date_setup_icon)
                                                    .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                                @endif
                                            </h4>
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
                                        <a class="link_related_new_hot" href="{{ route('module.news.detail',['id' => $get_related_new_hot_outside->id]) }}">
                                            <h4 class="title_related_new_hot"><strong>{{ $get_related_new_hot_outside->title }}</strong>
                                                @if ($date_now < $get_related_new_hot_outside->date_setup_icon)
                                                    .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                                @endif
                                            </h4>
                                            <div class="show_all_related_new_hot">
                                                {{ $get_related_new_hot_outside->title }}
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
                        @livewire('news.catenews', ['type' => $type, 'category_id' => $cate_id, 'parent_id' => $parent_id, 'object_news_parent_cate_id' => $object_news_parent_cate_id])
                    </div>
                </div>

                @include('news::frontend.news_right')
            </div>
        </div>
    </div>
@stop
@section('footer')
    <script type="text/javascript">
        $(window).scroll(function() { 
            if($(window).scrollTop() + $(window).height() >= $(document).height()) { 
                window.livewire.emit('load-more'); 
            }
        }); 
    </script>
@endsection

