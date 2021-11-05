@extends('layouts.app')

@section('page_title', $item->name)

@section('header')
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue.min.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('styles/module/qrcode/js/vue-qrcode-reader.browser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('styles/module/qrcode/css/vue-qrcode-reader.css') }}">
@endsection

@section('content')
    <div class="_215b01">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                            <a href="{{ route('frontend.all_course',['type' => 0]) }}">@lang('app.course')</a>
                            <i class="uil uil-angle-right"></i>
                            <a href="{{ route('frontend.all_course',['type' => 2]) }}">@lang('app.off_course')</a>
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">{{ $item->name }}</span>
                        </h2>
                    </div>
                </div>
            </div>
            <p></p>
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="preview_video">
                        <div class="row justify-content-center">
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="preview_video">
                                    <a href="#" class="fcrse_img" data-toggle="modal" data-target="#videoModal">
                                        <img src="{{ image_file($item->image) }}" alt="">
                                        <div class="course-overlay">
                                            @if ($item->pointSetting)
                                                @php
                                                    if ($item->pointSetting->method == 1)
                                                        $point = $item->pointSetting->point;
                                                    else{
                                                        $setting = $item->pointSetting->methodSetting->sortByDesc('point');
                                                        $point = $setting->count() > 0 ? $setting->first()->point : 0;
                                                    }
                                                @endphp
                                            <div class="badge_seller">{{ $point }} <img class="point ml-1" style="width: 20px;height: 20px" src="{{ asset('images/level/point.png') }}" alt=""></div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                                <div class="_215b05">
                                    <a href="javascript:void(0)" class="_215b05">
                                        <span><i class="uil uil-windsock"></i></span>{{ $item->register->count() }} @lang('app.joined')
                                    </a>
                                    <a href="javascript:void(0)" class="_215b05">
                                        <span>
                                            <i class='uil uil-heart {{ $item->bookmarked ? 'check-heart' : ''}}'></i>
                                        </span> {{ $item->bookmarked ? __('app.bookmarked') : __('app.bookmark') }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <div class="_215b05">
                                    <h2>{{ $item->name }}</h2>
                                    <span class="_215b05">{{ \Illuminate\Support\Str::words($item->description, 20) }}</span>
                                </div>
                                <div class="_215b05">
                                    <div class="crse_reviews mr-2">
                                        <i class="uil uil-star"></i>{{ $item->avgRatingStar() }}
                                    </div>
                                    ({{ $item->countRatingStar() }} lượt đánh giá)
                                </div>

                                <div class="_215b05">

                                </div>

                                <div class="_215b05">

                                    <div class="_215b05">
                                        <span><i class='uil uil-eye'></i></span>
                                        {{ trans('app.view') }}
                                    </div>
                                    @php
                                        switch ($course_time_unit){
                                            case 'day': $time_unit = 'Ngày'; break;
                                            case 'session': $time_unit = 'Buổi'; break;
                                            default : $time_unit = 'Giờ'; break;
                                        }
                                    @endphp
                                    <div class="_215b05">
                                        <span><i class='uil uil-clock'></i></span>
                                        Thời lượng: {{ $course_time.' '.$time_unit }}
                                    </div>
                                </div>
                                @php
                                    $status = $item->getStatusRegister();
                                    $text = $text_status($status);
                                @endphp
                                <div class="_215b05">
                                    <b>@lang('app.time'):</b> {{ get_date($item->start_date) }} @if($item->end_date) đến {{ get_date($item->end_date) }} @endif
                                </div>

                                <div class="_215b05">
                                    <b>@lang('app.register_deadline'):</b> {{ get_date($item->register_deadline) }}
                                </div>

                                @if($item->getObject())
                                    <div class="_215b05">
                                        <b>Đối tượng học viên:</b> {{ $item->getObject() }}
                                    </div>
                                @endif
                                <ul class="_215b05">
                                    <div class="mt-2">
                                        @php
                                            $promotion_share = \Modules\Promotion\Entities\PromotionShare::query()
                                                ->where('user_id', '=', \Auth::id())
                                                ->where('course_id', '=', $item->id)
                                                ->where('type', '=', 2)
                                                ->first();
                                        @endphp
                                        @if($promotion_share)
                                            <b>Link share:</b> {{ route('module.offline.detail', ['id' => $item->id]).'?share_key='. $promotion_share->share_key }}
                                        @else
                                            <button href="javascript:void(0)" class="btn btn-info" id="share-course">Share</button>
                                        @endif
                                    </div>

                                    @if($status == 1)
                                        <div class="mt-2 item item-btn">
                                            <button onclick="submitRegister()" class="btn btn_adcart">{{ $text }}</button>
                                        </div>
                                    @elseif($status == 4)
                                        <div class="mt-2">
                                            <button href="javascript:void(0)" class="btn btn_adcart" id="go-course">{{ mb_strtoupper($text) }}</button>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <button type="button" class="btn btn_adcart">{{ $text }}</button>
                                        </div>
                                    @endif

                                    @if($item->rating && $register)
                                        @if($item->rating_end_date && $item->rating_end_date < date('Y-m-d H:i:s'))
                                            <div class="mt-2">
                                                Đã kết thúc thời gian đánh giá sau khóa học
                                            </div>
                                        @else
                                        <div class="mt-2">
                                            <a id="review_course" href="{{ isset($rating_course) ? route('module.rating.edit_course', ['type' => 2, 'id' => $item->id]) : route('module.rating.course', ['type' => 2, 'id' => $item->id]) }}" class="btn btn-info"> Đánh giá sau khóa học</a>
                                        </div>
                                        @endif
                                    @endif

                                    <div id="notify-course" class="">@lang('app.notify_go_course')</div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-12 pl-0">
                    <div class="_215b15 _byt1458">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="course_tabs">
                                        <nav>
                                            <div class="nav nav-tabs tab_crse justify-content-center" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-selected="true">@lang('app.description')</a>
                                                <a class="nav-item nav-link" id="nav-courses-tab" data-toggle="tab" href="#nav-courses" role="tab" aria-selected="false">@lang('app.content')</a>
                                                <a class="nav-item nav-link" id="nav-reviews-tab" data-toggle="tab" href="#nav-reviews" role="tab" aria-selected="false">@lang('app.comment')</a>
                                                <a class="nav-item nav-link" id="nav-rating-level-tab" data-toggle="tab" href="#nav-rating-level" role="tab" aria-selected="false"> Đánh giá hiệu quả đào tạo ({{ $count_rating_level }})</a>
                                            </div>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="_215b17">
                        <div class="container-fluid body_course_offline">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="course_tab_content">
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-about" role="tabpanel">
                                                <div class="_htg451">
                                                    {!! $item->content !!}
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-courses" role="tabpanel">
                                                <div class="crse_content">
                                                    <div class="row">
                                                    @php
                                                        $documents = json_decode($item->document)
                                                    @endphp
                                                    @if ( !empty($documents) )
                                                        @foreach ($documents as $key => $document)
                                                            @if($item->checkPdf( $item->id,$key) )
                                                                <a href="{{ route('module.offline.view_pdf', ['id' => $item->id, 'key' => $key]) }}" target="_blank" class="btn btn_adcart click-view-doc mb-2" data-id="{{$item->id}}" >
                                                                    <i class="fa fa-download" aria-hidden="true"></i> {{ basename($document) }}
                                                                </a>
                                                            @else
                                                                <a href="{{ link_download('uploads/'.$document) }}" data-turbolinks="false" >
                                                                    <i class="fa fa-download" aria-hidden="true"></i> {{ basename($document) }}
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-reviews" role="tabpanel">
                                                @livewire('offline.comment', ['course_id' => $item->id,'avg_star' => $item->avgRatingStar()])
                                            </div>
                                            <div class="tab-pane fade" id="nav-rating-level" role="tabpanel">
                                                <table class="tDefault table table-hover bootstrap-table" id="table-rating-level">
                                                    <thead>
                                                    <tr>
                                                        <th data-field="rating_url" data-formatter="rating_url_formatter" data-align="center">Đánh giá</th>
                                                        <th data-field="rating_name">Tên đánh giá</th>
                                                        <th data-field="rating_time">Thời gian đánh giá</th>
                                                        <th data-field="rating_status" data-align="center">Tình trạng</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('offline::modal.referer')
    <script>
        window.Rating = {
            route: '{{ route('module.offline.rating',$item->id) }}',
        };
        var rating = $('.rating');
        ratingStars(rating);

        $('#notify-course').prop('hidden', true);

        $("#go-course").on('click', function () {
            $('a[data-toggle="tab"]').removeClass('active');
            $('a[data-toggle="tab"]').attr('aria-selected',false);

            $('a[href="#nav-courses"]').attr('aria-selected',true);
            $('a[href="#nav-courses"]').addClass('active');

            $('#nav-tabContent .tab-pane').removeClass('show active');
            $('#nav-courses').addClass('show active');

            $('#go-course').prop('hidden', true);
            $('#notify-course').prop('hidden', false);
        });

        $('#share-course').on('click', function () {
            var share_key = Math.random().toString(36).substring(3);
            $.ajax({
                type: "POST",
                url: "{{ route('module.offline.detail.share_course', ['id' => $item->id, 'type' => 2]) }}",
                data:{
                    share_key: share_key,
                },
                success: function (data) {
                    window.location = '';
                }
            });
        });

        function rating_url_formatter(value, row, index) {
            if(row.rating_level_url){
                return '<a href="'+ row.rating_level_url +'">Đánh giá</a>';
            }
            return 'Đánh giá';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.detail.rating_level.getdata', ['id' => $item->id]) }}',
            table: '#table-rating-level',
        });

        function submitRegister() {
            var form =  $('#frm-course');
            form.submit();
        }
    </script>
@stop
