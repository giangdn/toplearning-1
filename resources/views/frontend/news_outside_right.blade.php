@if (!$get_news_category_sort_right->isEmpty())
    @foreach ($get_news_category_sort_right as $get_new_category_sort_right)
    @php
        $get_news_right = Modules\NewsOutside\Entities\NewsOutside::select(['id','image','description','title','date_setup_icon'])
            ->where('category_id',$get_new_category_sort_right->id)
            ->where('status',1)
            ->orderBy('hot','DESC')
            ->orderBy('created_at','DESC')
            ->take(3)
            ->get();
    @endphp
    @if (!$get_news_right->isEmpty())
        <div class="all_news mb-3 pt-2">
            <div class="row">
                <div class="col-6 mb-1"><h5><span>{{ $get_new_category_sort_right->name }}</span></h5></div>
                @if (count($get_news_right) >= 3)
                    <div class="col-6 py-1" style="text-align: right">
                        <a href="{{ route('module.frontend.news_outside', ['cate_id' => $get_new_category_sort_right->id, 'parent_id' => $get_new_category_sort_right->parent_id, 'type' => 1]) }}">
                            <p>Xem thêm <img src="{{asset('images/right-arrow.png')}}" alt=""></p>
                        </a>
                    </div>
                @endif
            </div>

            @foreach ($get_news_right as $get_new_right)
                <div class="row mb-3 get_new_right">
                    <div class="col-5 pr-0">
                        <a href="{{ route('detail_home_outside',['id' => $get_new_right->id, 'type' => $type]) }}">
                            <img class="w-100" src="{{ image_file($get_new_right->image) }}" height="auto" alt="">
                        </a>
                    </div>
                    <div class="col-7 new_right">
                        <div class="hot_new_title">
                            <a class="link_hot_new_title_right" href="{{ route('detail_home_outside',['id' => $get_new_right->id, 'type' => $type]) }}">
                                <p class="mb-1">{{ $get_new_right->title }}
                                    @if ($date_now < $get_new_right->date_setup_icon)
                                        .<img class="icon_new" src="{{ asset('images/new1.gif') }}" alt="">
                                    @endif
                                </p>
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