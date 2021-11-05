@extends('layouts.app_outside')

@section('page_title', 'HỆ THỐNG QUẢN LÝ ĐÀO TẠO E-LEARNING')

@section('content')
    @php
        $date_now = date('Y-m-d H:s');
        $logout = session()->get('logout') ? session()->get('logout') : 0;
        $url = session()->get('url_previous') ? session()->get('url_previous') : '';
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
        <div class="body_outside row my-2">
            <div class="content_left col-md-8 col-12">
                @if ($get_main_new_hot)
                    <div class="all_hot_news mt-2">
                        <div class="row">
                            <div class="col-8"><h5><span>Tin Tức Nổi bật</span></h5></li></div>
                        </div>
                        <div class="row get_hot_main_new">
                            @if (!$get_hot_news->isEmpty())
                                <div class="hot_main_new col-md-5 col-12 mb-3">
                                    @php
                                        $created_at_hot_main_new = \Carbon\Carbon::parse($get_main_new_hot->created_at)->format('H:s d/m/Y');
                                    @endphp
                                    <span>Ngày đăng: {{ $created_at_hot_main_new }}
                                        @if ($date_now < $get_main_new_hot->date_setup_icon)
                                            .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                        @endif
                                    </span>
                                    <div class="mt-1">
                                        <a href="{{ route('detail_home_outside',['id' => $get_main_new_hot->id, 'type' => $type]) }}">
                                            <img class="w-100" src="{{ image_file($get_main_new_hot->image) }}" height="auto" alt="">
                                        </a>
                                    </div>
                                    <div class="main_new">
                                        <div>
                                            <a class="link_title_main_new" href="{{ route('detail_home_outside',['id' => $get_main_new_hot->id, 'type' => $type]) }}">
                                                <h5 class="my-1 title_main_new">{{ $get_main_new_hot->title }}</h5>
                                                <div class="show_all_title_main_new">
                                                    {{ $get_main_new_hot->title }}
                                                </div>
                                            </a>
                                        </div>
                                        <div class="main_new_hot_description">
                                            {!! $get_main_new_hot->description !!}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-4">
                                    <a href="{{ route('detail_home_outside',['id' => $get_main_new_hot->id, 'type' => $type]) }}">
                                        <img class="w-100" src="{{ image_file($get_main_new_hot->image) }}" height="auto" alt="">
                                    </a>
                                </div>
                                <div class="col-md-8">
                                    <div class="hot_new_title">
                                        <a href="{{ route('detail_home_outside',['id' => $get_main_new_hot->id, 'type' => $type]) }}">
                                            <h4 class="mb-2">{{ $get_main_new_hot->title }}</h4>
                                        </a>
                                    </div>
                                    <div class="hot_new_description">
                                        {!! $get_main_new_hot->description !!}
                                    </div>
                                </div>
                            @endif
                            @if (!$get_hot_news->isEmpty())
                                <div class="hot_news col-md-7 col-12">
                                    @foreach ($get_hot_news as $get_hot_new)
                                        <div class="row mb-2 mx-0 get_hot_news">
                                            <div class="col-5 p-0">
                                                <a href="{{ route('detail_home_outside',['id' => $get_hot_new->id, 'type' => $type]) }}">
                                                    <img class="w-100" src="{{ image_file($get_hot_new->image) }}" height="auto" alt="">
                                                </a>
                                            </div>
                                            <div class="col-7 pr-0 hot_new">
                                                <div class="hot_new_title">
                                                    <a class="link_hot_new_title" href="{{ route('detail_home_outside',['id' => $get_hot_new->id, 'type' => $type]) }}">
                                                        <p class="mb-1">{{ $get_hot_new->title }}
                                                            @if ($date_now < $get_hot_new->date_setup_icon)
                                                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                                            @endif
                                                        </p>
                                                        <div class="show_all_hot_new_title">
                                                            {{ $get_hot_new->title }}
                                                            @if ($date_now < $get_hot_new->date_setup_icon)
                                                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="hot_new_description">
                                                    {!! $get_hot_new->description !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                @if (!$get_news_parent_cate_left->isEmpty())
                    @foreach ($get_news_parent_cate_left as $get_news_parent_cate_left)
                        @php
                            $get_news_cate_child = Modules\NewsOutside\Entities\NewsOutsideCategory::where('parent_id',$get_news_parent_cate_left->id)
                                ->where('sort',0)->orderBy('stt_sort','asc')->get();

                            $get_news_cate_child_array = Modules\NewsOutside\Entities\NewsOutsideCategory::where('parent_id',$get_news_parent_cate_left->id)
                                ->where('sort',0)->orderBy('stt_sort','asc')->pluck('id')->toArray();

                            $get_hot_news_of_cate_child = Modules\NewsOutside\Entities\NewsOutside::select(['id','image','description','title','date_setup_icon'])->where('category_parent_id',$get_news_parent_cate_left->id)->whereIn('category_id',$get_news_cate_child_array)->orderByDesc('hot')->orderByDesc('created_at')->where('status',1)->get()->take(4);
                        @endphp
                        @if (!empty($get_hot_news_of_cate_child))
                            <div class="all_news my-3 pt-2">
                                <div class="row">
                                    <div class="col-12 mb-1 title_cate_left">
                                        <a href="{{ route('module.frontend.news_outside', ['cate_id' => 0, 'parent_id' => $get_news_parent_cate_left->id, 'type' => 0]) }}">
                                            <h6>
                                                {{ $get_news_parent_cate_left->name }}
                                            </h6>
                                        </a>
                                        
                                        <ul class="cate_child_left">
                                            @foreach ($get_news_cate_child as $get_new_cate_child)
                                                <li>
                                                    <a href="{{ route('module.frontend.news_outside', ['cate_id' => $get_new_cate_child->id, 'parent_id' => $get_news_parent_cate_left->id, 'type' => 1]) }}">
                                                        {{ $get_new_cate_child->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-12 description_new_left">
                                        <div class="row">
                                            @if (!empty($get_hot_news_of_cate_child))
                                                @foreach ($get_hot_news_of_cate_child as $get_hot_new_of_cate_child)
                                                    <div class="col-md-6 col-12 my-2 hot_new_cate_left">
                                                        <div class="row">
                                                            <div class="col-5 pr-0">
                                                                <a href="{{ route('detail_home_outside',['id' => $get_hot_new_of_cate_child->id, 'type' => $type]) }}">
                                                                    <img class="w-100" src="{{ image_file($get_hot_new_of_cate_child->image) }}" height="auto" alt="">
                                                                </a>
                                                            </div>
                                                            <div class="col-7 hot_new_left pr-2">
                                                                <div>
                                                                    <a class="link_hot_new_left" href="{{ route('detail_home_outside',['id' => $get_hot_new_of_cate_child->id, 'type' => $type]) }}">
                                                                        <h6 class="title_hot_new_left">{{ $get_hot_new_of_cate_child->title }}
                                                                            @if ($date_now < $get_hot_new_of_cate_child->date_setup_icon)
                                                                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                                                            @endif
                                                                        </h6>
                                                                        <div class="show_all_title_hot_new_left">
                                                                            {{ $get_hot_new_of_cate_child->title }}
                                                                            @if ($date_now < $get_hot_new_of_cate_child->date_setup_icon)
                                                                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                                                            @endif
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                                <div class="main_new_hot_description">
                                                                    {{ $get_hot_new_of_cate_child->description }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="content_right col-md-4 col-12">
                @include('frontend.news_outside_right')
                @include('frontend.exchange_rate')
            </div>
        </div>
        @include('layouts.footer_outside')
    </div>
    <script>
        var type = '<?php echo $type ?>';
        var logout = '<?php echo $logout ?>';
        var url = '<?php echo $url ?>';
        console.log(url);
        $(document).ready(function(){    
            if(document.URL.indexOf("#")==-1 && (type == 1 || logout == 1) ){
                url = document.URL+"#";
                location = "#";
                location.reload(true);
            }
        });
    </script>
@endsection
