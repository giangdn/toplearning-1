@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.preferential_stores'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 text-center bg-white p-2 border-bottom">
                <h5>@lang('app.preferential_exchange')</h5>
            </div>
            <div class="col-12 px-0">
                <div class="swiper-container promotion-slide">
                    <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                        <a class="swiper-slide nav-item nav-link active pl-0 pr-0" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-selected="true">
                            <img src="{{ asset('themes/mobile/img/crown.png') }}" alt="" class="avatar-20"> <br>
                            {{ trans('app.all') }}
                        </a>
                        @foreach($promotion_group as $key => $group)
                            <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-{{ $group->id }}tab" data-toggle="tab" href="#nav-{{ $group->id }}" role="tab" aria-selected="true">
                                <img src="{{ image_file($group->icon) }}" alt="" class="avatar-20"> <br>
                                {{ $group->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2">
                <h6>{{ data_locale('Quà tặng dành cho bạn', 'Incentives for you') }}</h6>
            </div>
            <div class="col-12">
                <div class="promotion_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-all" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @if(count($promotions) > 0)
                                    @foreach($promotions as $promotion)
                                        @include('themes.mobile.frontend.promotion.item')
                                    @endforeach
                                    <div class="row">
                                        <div class="col-6">
                                            @if($promotions->previousPageUrl())
                                                <a href="{{ $promotions->previousPageUrl() }}" class="bp_left">
                                                    <i class="material-icons">navigate_before</i> @lang('app.previous')
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-6 text-right">
                                            @if($promotions->nextPageUrl())
                                                <a href="{{ $promotions->nextPageUrl() }}" class="bp_right">
                                                    @lang('app.next') <i class="material-icons">navigate_next</i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-center">@lang('app.not_found')</span>
                                @endif
                            </div>
                        </div>
                        @foreach($promotion_group as $key => $group)
                            <div class="tab-pane fade" id="nav-{{ $group->id }}" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @php
                                        $promotion_by_group = \Modules\Promotion\Entities\Promotion::getPromotionByGroup($group->id);
                                    @endphp
                                    @if(count($promotion_by_group) > 0)
                                        @foreach($promotion_by_group as $promotion)
                                            @include('themes.mobile.frontend.promotion.item')
                                        @endforeach
                                        <div class="row">
                                            <div class="col-6">
                                                @if($promotion_by_group->previousPageUrl())
                                                    <a href="{{ $promotion_by_group->previousPageUrl() }}" class="bp_left">
                                                        <i class="material-icons">navigate_before</i> @lang('app.previous')
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="col-6 text-right">
                                                @if($promotion_by_group->nextPageUrl())
                                                    <a href="{{ $promotion_by_group->nextPageUrl() }}" class="bp_right">
                                                        @lang('app.next') <i class="material-icons">navigate_next</i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-center">@lang('app.not_found')</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-2">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto">
                                <img src="{{ \App\Profile::avatar() }}" alt="" class="avatar avatar-40">
                            </div>
                            <div class="col align-self-center">
                                <h5>{{ $promotion_user ? $promotion_user->name : '' }}</h5>
                            </div>
                            <div class="col-auto align-self-center text-center">
                                <b>{{ $promotion_user ? $promotion_user->point : '' }}</b>
                                <img class="point avatar-20" src="{{ asset('images/level/point.png') }}" alt="">
                            </div>
                        </div>
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

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab-promotion', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab-promotion');
            if(activeTab){
                $('a[data-toggle="tab"]').removeClass('active');
                $('#nav-tab a[href="' + activeTab + '"]').tab('show');
                $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
            }
        });

        var swiper = new Swiper('.promotion-slide', {
            slidesPerView: 3,
            spaceBetween: 0,
            breakpoints: {
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 0,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 0,
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                },
                320: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                }
            }
        });
    </script>
@endsection
