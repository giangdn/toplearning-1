<div class="content_right col-md-4 col-12">
    @if (!$get_news_category_sort_right->isEmpty())
        @foreach ($get_news_category_sort_right as $get_new_category_sort_right)
        @php
            $get_news_right = Modules\News\Entities\News::select(['id','image','title','date_setup_icon','description'])
            ->where('category_id',$get_new_category_sort_right->id)
            ->whereNotIn('id',$object_news_parent_cate_id)
            ->where('status',1)
            ->orderBy('hot','DESC')
            ->orderBy('created_at','DESC')
            ->take(3)->get();
        @endphp
        @if (!$get_news_right->isEmpty())
            <div class="all_news mb-3 pt-2">
                <div class="row">
                    <div class="col-6 mb-2">
                        <h4><strong><span>{{ $get_new_category_sort_right->name }}</span></strong></h4>
                    </div>
                    @if (count($get_news_right) >= 3)
                        <div class="col-6 py-1" style="text-align: right">
                            <a href="{{ route('module.news.cate_new', ['parent_id' => $get_new_category_sort_right->parent_id, 'id' => $get_new_category_sort_right->id, 'type' => 1]) }}">
                                <p>Xem thêm <img src="{{asset('images/right-arrow.png')}}" alt=""></p>
                            </a>
                        </div>
                    @endif
                </div>

                @foreach ($get_news_right as $get_new_right)
                    <div class="row mb-3 get_new_right">
                        <div class="col-5 pr-0">
                            <a href="{{ route('module.news.detail',['id' => $get_new_right->id]) }}">
                                <img class="w-100" src="{{ image_file($get_new_right->image) }}" alt="" height="auto" {{--style="object-fit: cover"--}}>
                            </a>
                        </div>
                        <div class="col-7 new_right">
                            <div class="hot_new_title_right">
                                <a class="link_hot_new_title_right" href="{{ route('module.news.detail',['id' => $get_new_right->id]) }}">
                                    <h6 class="mb-1 title_new_right">
                                       <strong>{{ $get_new_right->title }}</strong>
                                        @if ($date_now < $get_new_right->date_setup_icon)
                                            .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                        @endif
                                    </h6>
                                    <div class="show_all_hot_new_title_right">
                                        {{ $get_new_right->title }}
                                        @if ($date_now < $get_new_right->date_setup_icon)
                                            .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                        @endif
                                    </div>
                                </a>
                            </div>
                            <div class="new_right_description">
                                {{ $get_new_right->description }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        @endforeach
    @endif
    <div class="banner_outside">
        <div class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach($getAdvertisingPhotos as $key => $getAdvertisingPhoto)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <a href="{{ $getAdvertisingPhoto->url }}">
                            <img src="{{ image_file($getAdvertisingPhoto->image) }}" alt="" class="w-100" />
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
