@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.news_mobile'))

@section('content')
    <div class="container" id="news-page-mobile">
        <div class="row pt-2 pb-3 mx-0">
            <form action="{{ route('module.news') }}" id="form_search" method="GET" class="w-100">
                <input type="text" name="search" id="search" class="form-control w-100" placeholder="Nhập tên tin.." onchange="searchLibraries()">
            </form>
        </div>
        <div class="row">
            <div class="col-12 px-0">
                <div class="swiper-container news-mobile-slide pt-1">
                    <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                        @foreach ($cate_news as $key => $cate_new)
                            @php
                                $news = \Modules\News\Entities\News::select(['id'])->where('category_id', $cate_new->id)->get();
                            @endphp
                            @if (count($news) > 0)
                                <a class="swiper-slide nav-item nav-link {{ $key == 0 ? 'active' : '' }}" id="nav-news-{{ $cate_new->id }}-tab" data-toggle="tab" href="#nav-news-{{ $cate_new->id }}" role="tab" aria-selected="{{ $key == 0 ? 'true' : 'false'}}">{{ $cate_new->name }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 px-0">
                <div class="news_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        @foreach ($cate_news as $key => $cate_new)
                            @php
                                $news = \Modules\News\Entities\News::select(['id','image','user_view','title','view_time','created_at','views'])->where('category_id', $cate_new->id)->orderByDesc('created_at')->get();
                            @endphp
                            @if(count($news) > 0)
                                <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }} shadow-sm pb-0" id="nav-news-{{ $cate_new->id }}" role="tabpanel" style="background-color: unset; border-radius: unset">
                                    @foreach($news as $key => $new)
                                        @php
                                            $user_id = $new->created_by;
                                            $time = $new->view_time ? $new->view_time : $new->created_at;
                                        @endphp
                                        <div class="card shadow-sm mb-1" style="border-radius: unset;">
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-5 p-0" id="laster-news">
                                                        <img src="{{ image_file($new->image) }}" alt="" class="w-100 border-0" height="auto" style="object-fit: cover;max-height:120px">
                                                    </div>
                                                    <div class="col-7 pr-0 align-self-center">
                                                        <a href="{{ route('module.news.detail', ['id' => $new->id]) }}" >
                                                            <h6 class="title_hot_new_left">{{ sub_char($new->title, 15) }}</h6>
                                                        </a>
                                                        <div class="row">
                                                            <div class="col-auto pr-1">
                                                                <img src="{{ \App\Profile::avatar($user_id) }}" alt="" class="avatar avatar-30 mt-1">
                                                            </div>
                                                            <div class="col pl-1">
                                                                <span class="small">{{ \App\Profile::fullname($user_id) }}</span>
                                                                <p class="text-mute small">
                                                                    {{ $new->views }} @lang('app.view') -
                                                                    @if(\Carbon\Carbon::parse($time)->diffInDays() > 10)
                                                                        {{ get_date($time) }}
                                                                    @elseif(\Carbon\Carbon::parse($time)->diffInHours() > 24)
                                                                        {{ \Carbon\Carbon::parse($time)->diffInDays() .' '. trans('app.day_ago') }}
                                                                    @elseif(\Carbon\Carbon::parse($time)->diffInMinutes() > 30)
                                                                        {{ \Carbon\Carbon::parse($time)->diffInHours() .' '. trans('app.hour_ago') }}
                                                                    @else
                                                                        {{ \Carbon\Carbon::parse($time)->diffForHumans() }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                        
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        $('#nav-tab').on('click', '.nav-item', function () {
            $('a[data-toggle="tab"]').removeClass('active');
        });

        $(document).ready(function () {
            $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                localStorage.setItem('activeTab-news', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab-news');
            if (activeTab) {
                $('a[data-toggle="tab"]').removeClass('active');
                $('#nav-tab a[href="' + activeTab + '"]').tab('show');
                $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
            }
        });

        var swiper = new Swiper('.news-mobile-slide', {
            slidesPerView: 3,
            spaceBetween: 0,
        });

        function searchLibraries() {
            const elem = document.getElementById('search');
            if (elem === document.activeElement) {
                $('#form_search').submit();
            } 
        }
    </script>
@endsection
