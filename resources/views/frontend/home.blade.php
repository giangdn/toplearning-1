@extends('layouts.app')

@section('page_title', 'HỆ THỐNG QUẢN LÝ ĐÀO TẠO E-LEARNING')

@section('header')
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
@endsection

@section('content')
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox-content forum-container">
                        <h2 class="st_title"><i class="uil uil-apps"></i>
                            <span class="font-weight-bold">@lang('app.home')</span>
                        </h2>
                    </div>
                </div>
            </div>

            @include('data.course_overview')
            <div class="row">
                <div class="col-12">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            <h2>Bạn đã hoàn thành {{ $count_complete_course_by_user.'/'.$count_register_course_by_user }} khóa học</h2>
                        </div>
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <canvas id="chart_course_by_user" class="chartjs"></canvas>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h6 class="bg-info text-white p-2">Khóa học mới</h6>
                                    @foreach($get_course_new as $item)
                                        @php
                                            if ($item->type == 1){
                                                $route = route('module.online.detail_online', ['id' => $item->id]);
                                                $type = 'Online';
                                                $img = asset('images/dashboard/graduation-cap.svg');
                                            }else{
                                                $route = route('module.offline.detail', ['id' => $item->id]);
                                                $type = 'Offline';
                                                $img = asset('images/dashboard/training.svg');
                                            }
                                        @endphp
                                        <div class="new_links10">
                                            <div class="row">
                                                <div class="col-2">
                                                    @if($item->image)
                                                        <img src="{{ image_file($item->image) }}" alt="" class="w-100">
                                                    @else
                                                        <img src="{{ $img }}" alt="" class="w-100">
                                                    @endif
                                                </div>
                                                <div class="col-10">
                                                    <a href="{{ $route }}">{{ $item->name }}</a> <br>
                                                    ({{ $item->code }}) <br>
                                                    @lang('app.time'): {{ get_date($item->start_date) . ($item->end_date ? " - " .get_date($item->end_date) : "") }}
                                                    <span class="float-right">{{ $type }}</span>
                                                    <br>
                                                    @lang('app.register_deadline'): {{ get_date($item->register_deadline) }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
                        <div class="card-header">
                            @php
                                $level_subject_name = '';
                                    foreach($getLevelSubjectByUser as $item){
                                        $count_subject = $count_subject_by_level_subject($item->id);
                                        $count_subject_complete = $count_subject_by_level_subject($item->id, 1);

                                        $level_subject_name .= ($count_subject_complete .'/'. $count_subject.' chuyên đề '.$item->name.', ');
                                    }
                            @endphp
                            <h2>
                                Bạn đã hoàn thành {{ $count_complete_subject_by_user.'/'.$count_register_subject_by_user }} chuyên đề thuộc tháp đào tạo {{ $level_subject_name ? '(' . $level_subject_name .')' : '' }}
                            </h2>
                        </div>
                        <div class="card-body p-1">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <canvas id="chart_subject_by_user" class="chartjs"></canvas>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h6 class="bg-info text-white p-2">Chuyên đề thuộc tháp đào tạo</h6>
                                    <table id="tableroadmap" class="table table-bordered bootstrap-table table-striped" style="table-layout: fixed">
                                        <thead>
                                        <tr class="tbl-heading">
                                            <th data-field="subject_name">{{ trans('app.subject') }}</th>
                                            <th data-field="status" data-align="center">{{ trans('backend.status') }}</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @include('data.total_course_chart')
                @include('data.course_chart')
                @include('data.chart')
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="section3125 mt-2 pt-0 bg-white">
                        <h5 class="item_title mb-0 w-100 p-3">
                            <strong class="ml-3">@lang('app.onl_course')</strong> 
                            @if ( $my_onl->count() == 5)
                                <span class="float-right">
                                    <a href="{{route('module.frontend.user.my_course',['type'=>1])}}">
                                        Xem thêm <img src="{{asset('images/right-arrow.png')}}" alt="">
                                    </a>
                                </span>
                            @endif
                        </h5>
                        <div class="la5lo1">
                            <div class="fcrse_1">
                                <div class="fcrse_content">
                                    @foreach($my_onl as $key => $onl)
                                        <div class="new_links10">
                                            <div class="row">
                                                <div class="col-2">
                                                    <img src="{{ asset('images/dashboard/graduation-cap.svg') }}" alt="" class="w-100">
                                                </div>
                                                <div class="col-10">
                                                    <a href="{{ route('module.online.detail_online',[$onl->course_id]) }}">
                                                        {{ $onl->name }}
                                                    </a> <br>
                                                    ({{ $onl->code }}) <br>
                                                    @lang('app.time'): {{ \Illuminate\Support\Carbon::parse($onl->start_date)->format('d/m/Y') . ($onl->end_date ? " - " .\Illuminate\Support\Carbon::parse($onl->end_date)->format('d/m/Y') : "") }}
                                                    <br>
                                                    @lang('app.register_deadline'): {{ $onl->register_deadline ? \Illuminate\Support\Carbon::parse($onl->register_deadline)->format('d/m/Y') : '' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="section3125 mt-2 pt-0 bg-white">
                        <h5 class="item_title mb-0 w-100 p-3">
                            <strong class="ml-3">@lang('app.off_course')</strong> 
                            @if ( $my_off->count() == 5)
                                <span class="float-right">
                                    <a href="{{route('module.frontend.user.my_course',['type'=>2])}}">@lang('app.off_course')
                                        Xem thêm <img src="{{asset('images/right-arrow.png')}}" alt="">
                                    </a>
                                </span>
                            @endif
                        </h5>
                        <div class="la5lo1">
                            <div class="fcrse_1">
                                <div class="fcrse_content">
                                    @foreach($my_off as $key => $off)
                                        <div class="new_links10">
                                            <div class="row">
                                                <div class="col-2">
                                                    <img src="{{ asset('images/dashboard/training.svg') }}" alt="" class="w-100">
                                                </div>
                                                <div class="col-10">
                                                    <a href="{{ route('module.offline.detail',[$off->course_id]) }}">
                                                        {{ $off->name }}
                                                    </a> <br>
                                                    ({{ $off->code }}) <br>
                                                    @lang('app.time'): {{ \Illuminate\Support\Carbon::parse($off->start_date)->format('d/m/Y') . ($off->end_date ? " - " .\Illuminate\Support\Carbon::parse($off->end_date)->format('d/m/Y') : "") }}
                                                    <br>
                                                    @lang('app.register_deadline'): {{ $off->register_deadline ? \Illuminate\Support\Carbon::parse($off->register_deadline)->format('d/m/Y') : '' }}
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="section3125 mt-2 pt-0 bg-white">
                        <h5 class="item_title mb-1 p-3 ml-3">
                            <img src="{{ asset('styles/images/unread.svg') }}" alt="" class="w-5">
                            <strong>@lang('app.notify')</strong> 
                        </h5>
                        <div class="all_msg_bg">
                            @if ($notify->count() > 0)
                                @foreach($notify as $note)
                                    <div class="channel_my item all__noti5 p-2">
                                        <div class="profile_link">
                                            @if($note->important == 1)
                                                <i class="uil uil-star text-warning"></i>
                                            @endif
                                            <div class="pd_content">
                                                <h6>
                                                    <a href="{{ route('module.notify.view', ['id' => $note->id, 'type' => $note->type]) }}">
                                                        <span class="{{ $note->viewed == 1 ? 'text-black' : 'text-primary' }}">
                                                        {{ $note->subject }}
                                                        </span>
                                                    </a>
                                                </h6>
                                                {{--<p class="noti__text5">{!! $note->content  !!}</p>--}}
                                                <span class="nm_time">
                                                    {{ \Illuminate\Support\Carbon::parse($note->created_at)->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="channel_my item all__noti5">
                                    <div class="profile_link">
                                        <div class="pd_content">
                                            <h6>@lang('app.no_notification')</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @php
                    $count_commplete = 0;
                @endphp
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="section3125 mt-2 pt-0 bg-white">
                        <h5 class="item_title mb-0 p-3">
                            <strong class="ml-3">@lang('app.course_roadmap') (<span id="count-complete">0</span>{{ '/'. $training_roadmap_course->count() }})</strong>
                        </h5>
                        <div class="la5lo1">
                            <div class="fcrse_1">
                                <div class="fcrse_content">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-2 pr-0">
                                                    @lang('app.request')
                                                </div>
                                                <div class="col-10 pl-0">
                                                    <div class="progress progress2">
                                                        <div class="progress-bar w-100" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                            100% ({{ $training_roadmap_course->count() . data_locale(' Khóa', ' Course') }})
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-2 pr-0">
                                                    {{ data_locale('Bạn', 'You') }}
                                                </div>
                                                <div class="col-10 pl-0">
                                                    <div class="progress progress2">
                                                        <div class="progress-bar" style="background-color: green !important;" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" id="percent-you">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($training_roadmap_course as $item)
                                        @if($item->id)
                                            @php
                                                if ($item->course_type == 1){
                                                    $result = \Modules\Online\Entities\OnlineCourse::checkCompleteCourse($item->course_id, \Auth::id());
                                                    $route = route('module.online.detail_online', ['id' => $item->course_id]);
                                                    $type = 'Online';
                                                }else{
                                                    $result = \Modules\Offline\Entities\OfflineCourse::checkCompleteCourse($item->course_id, \Auth::id());
                                                    $route = route('module.offline.detail', ['id' => $item->course_id]);
                                                    $type = 'Offline';
                                                }

                                                if ($result == 1){
                                                    $count_commplete++;
                                                }
                                            @endphp
                                            <div class="new_links10">
                                                <div class="row">
                                                    <div class="col-md-1 col-2 pr-0">
                                                        <img src="{{ asset('images/dashboard/graduation-cap.svg') }}" alt="" class="w-100">
                                                    </div>
                                                    <div class="col-md-11 col-10">
                                                        <a href="{{ $route }}">
                                                            {{ $item->name }}
                                                        </a> <br>
                                                        ({{ $item->code }}) <br>
                                                        @lang('app.time'): {{ \Illuminate\Support\Carbon::parse($item->start_date)->format('d/m/Y') . ($item->end_date ? " - " .\Illuminate\Support\Carbon::parse($item->end_date)->format('d/m/Y') : "") }}
                                                        <span class="float-right">{{ $type }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var count_complete = '{{ $count_commplete }}';
        $('#count-complete').text(count_complete);

        var total = '{{$training_roadmap_course->count()}}';
        var percent = (count_complete/total)*100;
        var text = (isNaN(percent) ? 0 : percent.toFixed(2)) + '% (' + count_complete + "{{ data_locale(' Khóa', ' Course') }})";
        $('#percent-you').text(text);
        $('#percent-you').css('width', (isNaN(percent) ? 0 : percent) + '%');

        var chart_course_by_user = document.getElementById("chart_course_by_user").getContext('2d');
        if (chart_course_by_user !== null) {
            var data_chart_course_by_user = {
                labels: ["{{ __('app.not_learned') }}", "{{ __('app.uncomplete') }}", "{{ __('app.completed') }}"],
                datasets: [{
                    backgroundColor: [
                        "#8b1409",
                        "#FEF200",
                        "#17a2b8",
                    ],
                    fill: false,
                    data: [{{ implode(',',$chartCourseByUser['course_by_user']) }}],
                }]
            };
            var options_chart_course_by_user = {
                legend: {
                    display: true,
                    position: 'bottom',

                },
                showTooltips: true,
                elements: {
                    arc: {
                        backgroundColor: "#8b1409",
                        hoverBackgroundColor: '#8b1409'
                    },
                },
            };
            var chartCourseNewByUser = new Chart(chart_course_by_user, {
                type: 'pie',
                data: data_chart_course_by_user,
                options: options_chart_course_by_user
            })
        }

        var table_roadmap = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('frontend.home.user_roadmap.getDataRoadmap') }}',
            table: '#tableroadmap',
        });

        var chart_subject_by_user = document.getElementById("chart_subject_by_user").getContext('2d');
        if (chart_subject_by_user !== null) {
            var data_chart_subject_by_user = {
                labels: ["{{ __('app.uncomplete') }}", "{{ __('app.completed') }}"],
                datasets: [{
                    backgroundColor: [
                        "#FEF200",
                        "#8b1409",
                    ],
                    fill: false,
                    data: [{{ implode(',', $chartSubjectByUser) }}],
                }]
            };
            var options_chart_subject_by_user = {
                legend: {
                    display: true,
                    position: 'bottom',

                },
                showTooltips: true,
                elements: {
                    arc: {
                        backgroundColor: "#8b1409",
                        hoverBackgroundColor: '#8b1409'
                    },
                },
            };
            var chartSubjectByUser = new Chart(chart_subject_by_user, {
                type: 'pie',
                data: data_chart_subject_by_user,
                options: options_chart_subject_by_user
            })
        }
    </script>
@endsection
