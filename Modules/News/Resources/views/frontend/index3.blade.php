@extends('layouts.app')

@section('page_title', trans('app.news'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/menu_new_inside.css') }}">
@php
    $date_now = date('Y-m-d H:s');
@endphp
<div class="sa4d25">
    <div class="container-fluid home-page-outside">
        @include('news::frontend.menu_new')
        <div class="row my-2 pt-2">
            <div class="content_left col-md-8 col-12">
                @if ($get_main_new_hot)
                    <div class="all_hot_news mt-2">
                        <div class="row">
                            <div class="col-8">
                                <h4 class="hot_public_title mb-2"><strong><span>Tin Tức Nổi bật</span></strong></h4>
                            </div>
                        </div>
                        <div class="row get_hot_main_new">
                            @if ( !empty($get_hot_news) )
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
                                        <a href="{{ route('module.news.detail',['id' => $get_main_new_hot->id]) }}">
                                            <img class="w-100" src="{{ image_file($get_main_new_hot->image) }}" alt="" {{--style="object-fit: cover"--}} height="auto">
                                        </a>
                                    </div>
                                    <div class="main_new">
                                        <div>
                                            <a class="link_title_main_new" href="{{ route('module.news.detail',['id' => $get_main_new_hot->id]) }}">
                                                <h5 class="title_main_new my-1"><strong>{{ $get_main_new_hot->title }}</strong></h5>
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

                                <div class="hot_news col-md-7 col-12">
                                    @foreach ($get_hot_news as $get_hot_new)
                                        <div class="row mb-2 mx-0 get_hot_news">
                                            <div class="col-5 p-0">
                                                <a href="{{ route('module.news.detail',['id' => $get_hot_new->id]) }}">
                                                    <img class="w-100" src="{{ image_file($get_hot_new->image) }}" alt="" height="auto" {{--style="object-fit: cover"--}} height="auto">
                                                </a>
                                            </div>
                                            <div class="col-7 pr-0 hot_new">
                                                <div class="hot_new_title">
                                                    <a class="link_hot_new_title" href="{{ route('module.news.detail',['id' => $get_hot_new->id]) }}">
                                                        <h6 class="mb-1"><strong>{{ $get_hot_new->title }}</strong>
                                                            @if ($date_now < $get_hot_new->date_setup_icon)
                                                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                                            @endif
                                                            <div class="show_all_hot_new_title">
                                                                {{ $get_hot_new->title }}
                                                                @if ($date_now < $get_hot_new->date_setup_icon)
                                                                    .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                                                @endif
                                                            </div>
                                                        </h6>
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
                            $get_news_cate_child = Modules\News\Entities\NewsCategory::where('parent_id',$get_news_parent_cate_left->id)
                                ->where('sort',0)->orderBy('stt_sort','asc')->get();

                            $get_news_cate_child_array = Modules\News\Entities\NewsCategory::where('parent_id',$get_news_parent_cate_left->id)
                                ->where('sort',0)->orderBy('stt_sort','asc')->pluck('id')->toArray();

                            $get_hot_news_of_cate_child = Modules\News\Entities\News::select(['id','image','title','date_setup_icon','description'])->whereNotIn('id',$object_news_parent_cate_id)->where('category_parent_id',$get_news_parent_cate_left->id)->whereIn('category_id',$get_news_cate_child_array)->orderByDesc('hot')->orderByDesc('created_at')->where('status',1)->get()->take(4);
                        @endphp
                        @if (!empty($get_hot_news_of_cate_child))
                            <div class="all_news my-3 pt-2">
                                <div class="row">
                                    <div class="col-12 mb-1 title_cate_left">
                                        <a href="{{ route('module.news.cate_new', ['parent_id' => $get_news_parent_cate_left->id, 'id' => 0, 'type' => 0]) }}">
                                            <h6>
                                                <strong>{{ $get_news_parent_cate_left->name }}</strong>
                                            </h6>
                                        </a>

                                        <ul class="cate_child_left">
                                            @foreach ($get_news_cate_child as $get_new_cate_child)
                                                <li>
                                                    <a href="{{ route('module.news.cate_new', ['parent_id' => $get_news_parent_cate_left->id, 'id' => $get_new_cate_child->id,  'type' => 1]) }}">
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
                                                            <div class="col-6 pr-0">
                                                                <a href="{{ route('module.news.detail',['id' => $get_hot_new_of_cate_child->id]) }}">
                                                                    <img class="w-100" src="{{ image_file($get_hot_new_of_cate_child->image) }}" alt="" height="auto" {{--style="object-fit: cover"--}}>
                                                                </a>
                                                            </div>
                                                            <div class="col-6 hot_new_left pr-2">
                                                                <div>
                                                                    <a class="link_hot_new_left" href="{{ route('module.news.detail',['id' => $get_hot_new_of_cate_child->id]) }}">
                                                                        <h6 class="my-1 title_hot_new_left"><strong>{{ $get_hot_new_of_cate_child->title }}</strong>
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
            @include('news::frontend.news_right')
        </div>
    </div>
</div>
@stop
