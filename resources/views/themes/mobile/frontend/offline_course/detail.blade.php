@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.in_house'))

@section('content')
    <div class="container">
        <div class="card shadow border-0 bg-template mb-2 mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 p-1">
                        <img src="{{ image_file($item->image) }}" alt="" class="w-100 picture_course">
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 p-1 text-white">
                        <h6 class="mt-1 font-weight-normal">{{ $item->name }}</h6>
                        <p class="text-justify">{{ \Illuminate\Support\Str::words($item->description, 20) }}</p>
                        <i class="material-icons vm text-warning">star</i> {{ $item->avgRatingStar() }}
                        <span class="float-right">({{ $item->countRatingStar() }} @lang('app.votes'))</span>
                        <br>
                            <i class="material-icons vm">remove_red_eye</i> {{ $item->views .' '. trans('app.view') }}
                            @php
                                switch ($course_time_unit){
                                    case 'day': $time_unit = trans('app.day'); break;
                                    case 'session': $time_unit = trans('app.session'); break;
                                    default : $time_unit = trans('app.hours'); break;
                                }
                            @endphp
                            <span class="float-right"><i class='material-icons vm'>timer</i>
                                @lang('app.duration'): {{ $course_time.' '.$time_unit }}
                            </span>
                        <br>
                            <b>@lang('app.time'): </b> {{ get_date($item->start_date) }} @if($item->end_date) {{' - '. get_date($item->end_date) }} @endif
                        <br>
                            <b>@lang('app.register_deadline'):</b> {{ get_date($item->register_deadline) }}
                            @if($item->getObject())
                                <p><b>@lang('app.trainee_object'):</b> {{ $item->getObject() }}</p>
                            @endif
                        <br>
                        @php
                            $status = $item->getStatusRegister();
                            $text = $text_status($status);
                        @endphp
                        @if($status == 1)
                            <form action="{{ route('module.offline.register_course', ['id' => $item->id]) }}" method="post" class="form-ajax">
                                @csrf
                                <div class="item item-btn">
                                    <button type="submit" class="btn btn-primary">{{ $text }}</button>
                                </div>
                            </form>
                        {{--@elseif($status == 4)
                            <button href="javascript:void(0)" class="btn btn-info" id="go-course">{{ mb_strtoupper($text) }}</button>--}}
                        @else
                            <button type="button" class="btn btn-danger">{{ $text }}</button>
                        @endif
                        <br>
                        @if($item->rating)
                            @if($item->rating_end_date && $item->rating_end_date < date('Y-m-d H:i:s'))
                                <div class="mt-2">
                                    Đã kết thúc thời gian đánh giá sau khóa học
                                </div>
                            @else
                            <div class="mt-2">
                                <a href="{{ isset($rating_course) ? route('module.rating.edit_course', ['type' => 2, 'id' => $item->id]) : route('module.rating.course', ['type' => 2, 'id' => $item->id]) }}" class="btn text-white btn-info"> Đánh giá sau khóa học</a>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="course_tabs">
                    <div class="col-12 px-0">
                        <div class="swiper-container offline-course-slide">
                            <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0 active" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-selected="true">@lang('app.description')</a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-courses-tab" data-toggle="tab" href="#nav-courses" role="tab" aria-selected="false">@lang('app.content')</a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-reviews-tab" data-toggle="tab" href="#nav-reviews" role="tab" aria-selected="false">@lang('app.comment')</a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-rating-level-tab" data-toggle="tab" href="#nav-rating-level" role="tab" aria-selected="true">
                                    {{ data_locale('Đánh giá đào tạo', 'Evaluate') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="course_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-about" role="tabpanel">
                            <div class="text-justify">
                                {!! $item->content !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-courses" role="tabpanel">
                            <div class="crse_content">
                                @php
                                    $documents = json_decode($item->document)
                                @endphp
                                @if ( !empty($documents) )
                                    @foreach ($documents as $key => $document)
                                        @if($item->checkPdf( $item->id,$key) )
                                            <a href="{{ route('module.offline.view_pdf', ['id' => $item->id, 'key' => $key]) }}" target="_blank" class="btn click-view-doc mb-2 text-white" data-id="{{$item->id}}" style="background-color: #1B4486; text-align: left;">
                                                <i class="fa fa-eye" aria-hidden="true"></i> {{ basename($document) }}
                                            </a>
                                        @else
                                            <a href="{{ link_download('uploads/'.$document) }}" data-turbolinks="false" class="text-white">
                                                <i class="fa fa-download" aria-hidden="true"></i> {{ basename($document) }}
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-reviews" role="tabpanel">
                            @include('themes.mobile.frontend.offline_course.comment')
                        </div>
                        <div class="tab-pane fade" id="nav-rating-level" role="tabpanel">
                            <table class="tDefault table table-hover bootstrap-table" id="table-rating-level">
                                <thead>
                                <tr>
                                    <th data-field="rating_url" data-formatter="rating_url_formatter" data-align="center">Đánh giá</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script type="text/javascript">
        var swiper = new Swiper('.offline-course-slide', {
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

        $('#nav-tab').on('click', '.nav-item', function () {
            $('a[data-toggle="tab"]').removeClass('active');
        });

        $(document).ready(function(){
            $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
                localStorage.setItem('activeTab-offline-course{{$item->id}}', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab-offline-course{{$item->id}}');
            if(activeTab){
                $('a[data-toggle="tab"]').removeClass('active');
                $('#nav-tab a[href="' + activeTab + '"]').tab('show');
                $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
            }
        });

        function rating_url_formatter(value, row, index) {
            var btn_rating_level_url = '<a href="#" class="btn btn-info text-white">Không thể đánh giá</a>';
            if(row.rating_level_url){
                btn_rating_level_url = '<a href="'+ row.rating_level_url +'" class="btn btn-info text-white">Đánh giá</a>';
            }

            return '<b>'+ row.rating_name +'</b> <br>' + 'Thời gian: ' + row.rating_time + '<br>' + 'Trạng thái '+ row.rating_status + '<br>' + btn_rating_level_url;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.offline.detail.rating_level.getdata', ['id' => $item->id]) }}',
            table: '#table-rating-level',
        });
    </script>
@endsection
