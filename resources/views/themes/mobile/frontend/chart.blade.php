@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.dashboard'))

@section('content')

<div class="container">
    <div class="card shadow h-200 overflow-hidden">
        <div class="h-200">
            <div id="linechart"></div>
        </div>
    </div>
</div>
<div class="container">
    <div class="card mb-4 shadow mt-1">
        <div class="card-body bg-none py-3">
            <div class="row">
                <div class="col text-right">
                    <p class="text-center"
                       id="chart-online">
                        <img src="{{ asset('themes/mobile/img/online-icon.png') }}"
                             alt=""
                             class="">
                        {{ $count_user_register_online }} <br>
                        <span
                              class="text-mute">@lang('app.online_course')</span>
                    </p>
                </div>
                <div class="col border-left-dotted">
                    <p class="text-center">
                        <img src="{{ asset('themes/mobile/img/history.png') }}"
                             alt=""
                             class=""> {{ $count_user_login }}<br>
                        <span class="text-mute">@lang('app.number_login')</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row bg-white shadow p-2">
        <div class="col-12 px-0 border-bottom">
            <h6 class="">@lang('app.categories')</h6>
        </div>
        <div class="swiper-container icon-slide pt-2">
            <div class="swiper-wrapper">
                <a href="{{ route('themes.mobile.frontend.my_course') }}"
                   class="swiper-slide text-center">
                    <div class="avatar avatar-40 no-shadow border-0">
                        <div class="overlay gradient-success"></div>
                        <img src="{{ asset('themes/mobile/img/my_course.png') }}"
                             alt="">
                    </div>
                    <p class="mt-2 small">@lang('app.my_course')</p>
                </a>
                @if(\App\Profile::usertype() != 2)
                <a href="{{ route('themes.mobile.frontend.online.index') }}"
                   class="swiper-slide text-center">
                    <div class="avatar avatar-40 no-shadow border-0">
                        <div class="overlay gradient-success"></div>
                        <img src="{{ asset('themes/mobile/img/online-learning.png') }}"
                             alt="">
                    </div>
                    <p class="mt-2 small">@lang('app.online_course')
                    </p>
                </a>
                <a href="{{ route('themes.mobile.frontend.offline.index') }}"
                   class="swiper-slide text-center">
                    <div class="avatar avatar-40 no-shadow border-0">
                        <div class="overlay gradient-warning"></div>
                        <img src="{{ asset('themes/mobile/img/offline.png') }}"
                             alt="">
                    </div>
                    <p class="mt-2 small">@lang('app.in_house')</p>
                </a>
                @endif
                <a href="{{ route('module.quiz.mobile') }}"
                   class="swiper-slide text-center">
                    <div class="avatar avatar-40 no-shadow border-0">
                        <div class="overlay gradient-danger"></div>
                        <img src="{{ asset('themes/mobile/img/quiz.png') }}"
                             alt="">
                    </div>
                    <p class="mt-2 small">@lang('app.quiz_mobile')</p>
                </a>
                {{--<a href="{{ route('module.libraries') }}"
                class="swiper-slide
                text-center">
                <div class="avatar avatar-40 no-shadow border-0">
                    <div class="overlay gradient-info"></div>
                    <img src="{{ asset('themes/mobile/img/library.png') }}"
                         alt="">
                </div>
                <p class="mt-2 small">Library </p>
                </a>--}}
                <a href="{{ route('module.news') }}"
                   class="swiper-slide text-center">
                    <div class="avatar avatar-40 no-shadow border-0">
                        <div class="overlay gradient-primary"></div>
                        <img src="{{ asset('themes/mobile/img/news.png') }}"
                             alt="">
                    </div>
                    <p class="mt-2 small">@lang('app.news') </p>
                </a>
                @if(\App\Profile::usertype() != 2)
                <a href="{{ route('module.frontend.forums') }}"
                   class="swiper-slide text-center">
                    <div class="avatar avatar-40 no-shadow border-0">
                        <div class="overlay gradient-primary"></div>
                        <img src="{{ asset('themes/mobile/img/forum.png') }}"
                             alt="">
                    </div>
                    <p class="mt-2 small">@lang('app.forum')</p>
                </a>
                @endif
                {{-- <a href="{{ route('module.career_roadmap.frontend') }}"
                class="swiper-slide text-center">
                <div class="avatar avatar-40 no-shadow border-0">
                    <div class="overlay gradient-primary"></div>
                    <img src="{{ asset('themes/mobile/img/roadmap.png') }}"
                         alt="">
                </div>
                <p class="mt-2 small">@lang('app.roadmap')</p>
                </a>--}}
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row bg-white shadow p-2 mt-3">
        <h6 class="col-12 px-0">
            @lang('app.course_roadmap') (<span
                  id="count-complete">0</span>{{ '/'. $training_roadmap_course->count() }})
            <a href="{{ route('themes.mobile.frontend.training_roadmap_course') }}"
               class="float-right small">
                <i class="material-icons">more_horiz</i>
            </a>
        </h6>
        <div class="container">
            <div class="row">
                <div class="col-2 p-0">
                    @lang('app.request')
                </div>
                <div class="col-10 p-0">
                    <div class="progress progress2">
                        <div class="progress-bar w-70"
                             role="progressbar"
                             style="width: 100%"
                             aria-valuenow="75"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            100%
                            ({{ $training_roadmap_course->count() . data_locale(' Khóa', ' Course') }})
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-2 p-0">
                    {{ data_locale('Bạn', 'You') }}
                </div>
                <div class="col-10 p-0">
                    <div class="progress progress2">
                        <div class="progress-bar w-70"
                             style="background-color: green;"
                             role="progressbar"
                             aria-valuenow="75"
                             aria-valuemin="0"
                             aria-valuemax="100"
                             id="percent-you">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container px-0 border-top pt-2">
            @php $count_commplete = 0; @endphp
            @if(count($training_roadmap_course) > 0)
            <div class="swiper-container offer-slide">
                <div class="swiper-wrapper">
                    @foreach($training_roadmap_course as $item)
                    @if($item->id)
                    @php
                    if ($item->course_type == 1){
                    $result =
                    \Modules\Online\Entities\OnlineCourse::checkCompleteCourse($item->id,
                    \Auth::id());
                    $percent =
                    \Modules\Online\Entities\OnlineCourse::percentCompleteCourseByUser($item->id,
                    \Auth::id());
                    $isRating =
                    \Modules\Online\Entities\OnlineRating::getRating($item->id,
                    \Auth::id());
                    $route =
                    route('themes.mobile.frontend.online.detail',
                    ['course_id' =>
                    $item->id]);
                    $type = 'Online';
                    }else{
                    $result =
                    \Modules\Offline\Entities\OfflineCourse::checkCompleteCourse($item->id,
                    \Auth::id());
                    $percent =
                    \Modules\Offline\Entities\OfflineCourse::percent($item->id,
                    \Auth::id());
                    $isRating =
                    \Modules\Offline\Entities\OfflineRating::getRating($item->id,
                    \Auth::id());
                    $route =
                    route('themes.mobile.frontend.offline.detail',
                    ['course_id'
                    => $item->id]);
                    $type = 'Tập trung';
                    }

                    if ($result == 1){
                    $count_commplete++;
                    }
                    @endphp
                    <div class="swiper-slide p-1">
                        <div class="card shadow border-0">
                            <div class="card-body p-1">
                                <div class="row h-100">
                                    <div class="col-auto">
                                        <img src="{{ image_file($item->image) }}"
                                             alt=""
                                             class="mw-100">
                                    </div>
                                    <div class="col">
                                        <h6 class="mt-2 font-weight-normal">
                                            <a
                                               href="{{ $route }}">{{ $item->name }}</a>
                                        </h6>
                                        <div class="rating-box">
                                            @for ($i = 1; $i < 6;
                                              $i++)
                                              <span
                                              class="rating-star 
                                                @if(!$isRating) empty-star rating 
                                                @elseif($isRating && $isRating->num_star >= $i) full-star 
                                                @endif"
                                              data-value="{{ $i }}">
                                                </span>
                                                @endfor
                                        </div>
                                        <span class="">
                                            @lang('app.time'):
                                            {{ get_date($item->start_date) }}
                                            @if($item->end_date)
                                            {{ ' - '. get_date($item->end_date) }}
                                            @endif
                                        </span>
                                        <br>
                                        <span class="">
                                            @lang('app.status'):
                                            {{ $result ? trans("backend.finish") : trans('backend.incomplete') }}
                                            <span
                                                  class="float-right">{{ $type }}</span>
                                            <br>
                                        </span>
                                        <br>
                                        <div class="progress progress2">
                                            <div class="progress-bar w-70"
                                                 role="progressbar"
                                                 style="width: {{ $percent }}%"
                                                 aria-valuenow="75"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                {{ round($percent, 2) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @else
            <p class="text-center">@lang('app.not_found')</p>
            @endif
        </div>
    </div>
</div>
<div class="container">
    @if($user_max_point)
    <div class="row bg-white shadow p-2 mt-3">
        <div class="col-12 px-0">
            <h6 class="">@lang('app.your_accumulated_points')</h6>
        </div>
        <div class="col-12 border-top pt-2">
            <div class="row align-items-center">
                <div class="col-auto pr-0">
                    <img src="{{ \App\Profile::avatar($user_max_point->user_id) }}"
                         alt=""
                         class="avatar avatar-50">
                </div>
                <div class="col align-self-center">
                    <p class="font-weight-normal mb-1">
                        {{ \App\Profile::fullname($user_max_point->user_id) }}
                    </p>
                    <p class="text-mute text-secondary">
                        {{ data_locale('Thành viên', 'Member') .' '. $user_max_point->name }}
                    </p>
                </div>
                <div class="col-auto border-left">
                    <h6 class="font-weight-normal mb-1">
                        {{ $user_max_point->point }}
                        <img class="point vm avatar-20 no-shadow"
                             src="{{ asset('images/level/point.png') }}"
                             alt="">
                    </h6>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<div class="container">
    <div class="row bg-white shadow p-2 mt-3"
         id="laster-news">
        <h6 class="col-12 px-0">
            @lang('app.latest_news')
            <a href="{{ route('module.news') }}"
               class="float-right small">
                <i class="material-icons">more_horiz</i>
            </a>
        </h6>
        <!-- Swiper -->
        <div class="swiper-container news-slide border-top pt-2">
            <div class="swiper-wrapper">
                @foreach($laster_news as $news)
                <div class="swiper-slide p-1 w-75">
                    <a
                       href="{{ route('module.news.detail', ['id' => $news->id]) }}">
                        <img src="{{ image_file($news->image) }}"
                             alt=""
                             class="mw-100">
                    </a>
                    <h6 class="font-weight-normal pl-1">
                        <a
                           href="{{ route('module.news.detail', ['id' => $news->id]) }}">{{ sub_char($news->title, 8) }}</a>
                    </h6>
                    <p class="small text-mute p-2">
                        @if(\Carbon\Carbon::parse($news->created_at)->diffInDays()
                        > 10)
                        {{ get_date($news->created_at) }}
                        @elseif(\Carbon\Carbon::parse($news->created_at)->diffInHours()
                        >
                        24)
                        {{ \Carbon\Carbon::parse($news->created_at)->diffInDays() .' '. trans('app.day_ago') }}
                        @elseif(\Carbon\Carbon::parse($news->created_at)->diffInMinutes()
                        > 30)
                        {{ \Carbon\Carbon::parse($news->created_at)->diffInHours() .' '. trans('app.hour_ago') }}
                        @else
                        {{ \Carbon\Carbon::parse($news->created_at)->diffForHumans() }}
                        @endif

                        <span class="float-right">
                            <a
                               href="{{ route('module.news.detail', ['id' => $news->id]) }}">
                                <i class="material-icons vm">arrow_forward</i>
                            </a>
                        </span>
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
    var count_complete = '{{ $count_commplete }}';
        $('#count-complete').text(count_complete);

        var total = '{{$training_roadmap_course->count()}}';
        var percent = (count_complete/total)*100;
        var text = (isNaN(percent) ? 0 : percent.toFixed(2)) + '% (' + count_complete + "{{ data_locale(' Khóa', ' Course') }})";
        $('#percent-you').text(text);
        $('#percent-you').css('width', (isNaN(percent) ? 0 : percent) + '%');

        var i = 0;
        $('#chart-online').on('click', function () {
            i += 1;
            var type = 'online';
            if (i%2 == 0){
                type = '';
                if (i == 10){
                    i = 0;
                }
            }
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var jsonData = $.ajax({
                    type: "POST",
                    url: "{{ route('themes.mobile.frontend.chart.data') }}",
                    dataType: "json",
                    async: false,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'type': type,
                    },
                }).responseText;

                jsonData = JSON.parse(jsonData);
                var data = google.visualization.arrayToDataTable(jsonData);

                var options = {
                    title: '',
                    curveType: 'function',
                    legend: { position: 'top' }
                };

                var chart = new google.visualization.LineChart(document.getElementById('linechart'));

                chart.draw(data, options);
            }
        });
        /* charts */
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var jsonData = $.ajax({
                type: "POST",
                url: "{{ route('themes.mobile.frontend.chart.data') }}",
                dataType: "json",
                async: false,
                data: {
                    "_token": "{{ csrf_token() }}",
                },
            }).responseText;

            jsonData = JSON.parse(jsonData);
            var data = google.visualization.arrayToDataTable(jsonData);

            var options = {
                title: '',
                curveType: 'function',
                legend: { position: 'top' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('linechart'));

            chart.draw(data, options);
        }
</script>
<!-- page level custom js -->
<script src="{{ asset('themes/mobile/js/statistics.js') }}"></script>
@endsection