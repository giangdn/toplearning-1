@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.news_mobile'))

@section('content')
@php
    $date_now = date('Y-m-d H:s');
@endphp
    <div class="container" id="news-page-mobile">
        <div class="row pt-2 pb-3 mx-0">
            <form action="{{ route('module.news') }}" id="form_search" method="GET" class="w-100">
                <select name="cate_id" class="select2 form-control w-100"  onchange="submit();">
                    <option value="" disabled selected>Danh mục</option>                                                    
                    @foreach ($parent_cates as $parent_cate)
                        @php
                            $check_new = Modules\News\Entities\News::where('category_parent_id',$parent_cate->id)->exists();
                        @endphp
                        @if ($check_new)
                            <option value="{{ $parent_cate->id }}">{{ $parent_cate->name }}</option>
                        @endif
                    @endforeach
                </select>
                <input type="text" name="search" id="search" class="form-control w-100" placeholder="Nhập tên tin.." onchange="searchLibraries()">
            </form>
        </div>
        <div class="row">
            <div class="content_left col-md-8 col-12">
                @if ($get_main_new_hot && !$cate_id && empty($news))
                    <div class="all_hot_news mt-2">
                        <div class="row">
                            <div class="col-8">
                                <h6 class="hot_public_title mb-2"><strong><span>Tin Tức Nổi bật</span></strong></h6>
                            </div>
                        </div>
                        <div class="row get_hot_main_new">
                            <div class="hot_main_new col-md-5 col-12 mb-3">
                                @php
                                    $created_at_hot_main_new = \Carbon\Carbon::parse($get_main_new_hot->created_at)->format('H:s d/m/Y');
                                @endphp
                                <span>Ngày đăng: {{ $created_at_hot_main_new }}
                                    @if ($date_now < $get_main_new_hot->date_setup_icon)
                                        .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="" width="40px" height="25px">
                                    @endif
                                </span>
                                <div class="mt-1">
                                    <a href="{{ route('module.news.detail',['id' => $get_main_new_hot->id]) }}">
                                        <img class="w-100" src="{{ image_file($get_main_new_hot->image) }}" alt="" height="200px" style="object-fit: cover">
                                    </a>
                                </div>
                                <div class="main_new">
                                    <div>
                                        <a class="link_title_main_new" href="{{ route('module.news.detail',['id' => $get_main_new_hot->id]) }}">
                                            <h6 class="title_main_new my-1"><strong>{{ $get_main_new_hot->title }}</strong></h6>
                                        </a>
                                    </div>
                                    <div class="main_new_hot_description">
                                        {!! $get_main_new_hot->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row news_item">
                            <div class="hot_news col-12">
                                @foreach ($get_hot_news as $key => $get_hot_new)
                                    @if ($key < 3)
                                        <div class="row mb-2 mx-0 get_hot_news">
                                            <div class="col-5 p-0">
                                                <a href="{{ route('module.news.detail',['id' => $get_hot_new->id]) }}">
                                                    <img class="w-100" src="{{ image_file($get_hot_new->image) }}" alt="" height="auto" style="object-fit: cover;max-height:120px">
                                                </a>
                                            </div>
                                            <div class="col-7 pr-0 hot_new">
                                                <div class="hot_new_title">
                                                    <a class="link_hot_new_title" href="{{ route('module.news.detail',['id' => $get_hot_new->id]) }}">
                                                        <h6 class="mb-1 title_hot_new"><strong>{{ $get_hot_new->title }}</strong>
                                                            @if ($date_now < $get_hot_new->date_setup_icon)
                                                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="" width="40px" height="25px">
                                                            @endif
                                                        </h6>
                                                    </a>
                                                </div>
                                                <div class="hot_new_description">
                                                    {!! $get_hot_new->description !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
                @if (!$get_news_parent_cate_left->isEmpty() && empty($news))
                    @foreach ($get_news_parent_cate_left as $get_news_parent_cate_left)
                        @php
                            $get_news_cate_child = Modules\News\Entities\NewsCategory::where('parent_id',$get_news_parent_cate_left->id)
                                ->where('sort',0)->orderBy('stt_sort','asc')->get();

                            $get_news_cate_child_array = Modules\News\Entities\NewsCategory::where('parent_id',$get_news_parent_cate_left->id)
                                ->where('sort',0)->orderBy('stt_sort','asc')->pluck('id')->toArray();

                            $get_hot_news_of_cate_child = Modules\News\Entities\News::select(['id','image','title','date_setup_icon','description'])->whereNotIn('id',$object_cate_parent)->where('category_parent_id',$get_news_parent_cate_left->id)->whereIn('category_id',$get_news_cate_child_array)->orderByDesc('hot')->orderByDesc('created_at')->where('status',1)->get()->take(3);
                        @endphp
                        @if (!$get_hot_news_of_cate_child->isEmpty())
                            <div class="all_news my-2">
                                <div class="row">
                                    <div class="col-12 mb-1 title_cate_left">
                                        <a href="{{ route('module.news.cate_new', ['parent_id' => $get_news_parent_cate_left->id, 'id' => 0, 'type' => 0]) }}">
                                            <h6>
                                                <strong>
                                                   <span>{{ $get_news_parent_cate_left->name }}</span> 
                                                </strong>
                                            </h6>
                                        </a>
                                    </div>
                                    <div class="col-12 description_new_left news_item">
                                        <div class="row">
                                            @if (!empty($get_hot_news_of_cate_child))
                                                @foreach ($get_hot_news_of_cate_child as $get_hot_new_of_cate_child)
                                                    <div class="col-md-6 col-12 my-2 hot_new_cate_left">
                                                        <div class="row">
                                                            <div class="col-5 pr-0">
                                                                <a href="{{ route('module.news.detail',['id' => $get_hot_new_of_cate_child->id]) }}">
                                                                    <img class="w-100" src="{{ image_file($get_hot_new_of_cate_child->image) }}" alt="" height="auto" style="object-fit: cover;max-height:120px">
                                                                </a>
                                                            </div>
                                                            <div class="col-7 hot_new_left pr-2">
                                                                <div>
                                                                    <a class="link_hot_new_left" href="{{ route('module.news.detail',['id' => $get_hot_new_of_cate_child->id]) }}">
                                                                        <h6 class="my-1 title_hot_new_left"><strong>{{ $get_hot_new_of_cate_child->title }}</strong>
                                                                            @if ($date_now < $get_hot_new_of_cate_child->date_setup_icon)
                                                                                .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="" width="40px" height="25px">
                                                                            @endif
                                                                        </h6>
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
                                        @if (count($get_hot_news_of_cate_child) == 3 )
                                            <div class="col-12 my-2">
                                                <a href="{{ route('module.news.cate_new', ['parent_id' => $get_news_parent_cate_left->id, 'id' => 0, 'type' => 0]) }}" class="see_more">
                                                    <p class="text-center">XEM THÊM</p>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                @if (!empty($news))
                    <div class="row get_hot_main_new">
                        @foreach ($news as $new)
                            <div class="hot_main_new col-md-5 col-12 mb-3">
                                @php
                                    $created_at_hot_main_new = \Carbon\Carbon::parse($new->created_at)->format('H:s d/m/Y');
                                @endphp
                                <span>Ngày đăng: {{ $created_at_hot_main_new }}
                                    @if ($date_now < $new->date_setup_icon)
                                        .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="" width="40px" height="25px">
                                    @endif
                                </span>
                                <div class="mt-1">
                                    <a href="{{ route('module.news.detail',['id' => $new->id]) }}">
                                        <img class="w-100" src="{{ image_file($new->image) }}" alt="" height="200px" style="object-fit: cover">
                                    </a>
                                </div>
                                <div class="main_new">
                                    <div>
                                        <a class="link_title_main_new" href="{{ route('module.news.detail',['id' => $new->id]) }}">
                                            <h6 class="title_main_new my-1"><strong>{{ $new->title }}</strong></h6>
                                        </a>
                                    </div>
                                    <div class="main_new_hot_description">
                                        {!! $new->description !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
    <script type="text/javascript"> 
        function searchLibraries() {
            const elem = document.getElementById('search');
            if (elem === document.activeElement) {
                $('#form_search').submit();
            } 
        }
    </script>
@endsection
