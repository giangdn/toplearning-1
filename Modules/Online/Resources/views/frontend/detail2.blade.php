@extends('layouts.app')

@section('page_title', 'Khóa học')

@section('header')
    <style>
        #nav-courses .accordion-header{
            border: none;
        }

        #nav-courses .activityItem{
            padding: 10px 0;
            cursor: pointer;
        }

        #nav-courses .line-active{
            border-left: 5px solid green;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-2 body_content_detail">
        <div class="row mb-2 header_bar">
            <div class="col-md-12">
                <div class="ibox-content forum-container">
                    <h2 class="st_title"><i class="uil uil-apps"></i>
                        <a href="{{ route('frontend.all_course',['type' => 0]) }}">@lang('app.course')</a>
                        <i class="uil uil-angle-right"></i>
                        <a href="{{ route('frontend.all_course',['type' => 1]) }}">@lang('app.onl_course')</a>
                        <i class="uil uil-angle-right"></i>
                        <span> <strong>{{ $item->name }}</strong></span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row nav_tab_menu">
            @if (!$agent->isMobile())
                <div class="col-1 pr-0">
                    <nav class="navbar navbar-dark menu_course">
                        <button class="navbar-toggler text-white border-0" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="true" aria-label="Toggle navigation" id="btnMenuListActivity">
                            <i class="uil uil-bars"></i>
                        </button>
                    </nav>
                </div>
            @endif
            @if ($agent->isMobile())
                <div class="col-12">
                    @include('online::frontend.mobile')
                </div>
            @else
                <div class="col-11 pl-0">
                    @include('online::frontend.web')
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="course_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade" id="nav-about" role="tabpanel">
                            <div class="my-3 text-justify">
                                <div class="row">
                                    <div class="col-12">
                                        <h3 class="p-3">Thông tin</h3>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <img class="icon_class" src="{{ asset('images/class.png') }}" alt="">
                                                        <span>Lớp học:</span>
                                                    </div>
                                                    <div class="col-7 text-center information_name">
                                                        <span>{{ $item->name }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <img class="icon_class" src="{{ asset('images/last_access.png') }}" alt="">
                                                        <span>Lần truy cập gần nhất:</span>
                                                    </div>
                                                    <div class="col-7 text-center information_access">
                                                        <span>{{ date("d-m-Y H:i", strtotime($time_user_view_course->time_view)) }}</span>
                                                    </div>
                                                </div>

                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <img class="icon_class" src="{{ asset('images/score.png') }}" alt="">
                                                        <span>Điểm tổng kết:</span>
                                                    </div>
                                                    <div class="col-7 text-center information_score">
                                                        <span>{{ !empty($get_result) ? $get_result->score : '0' }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <img class="icon_class" src="{{ asset('images/result.png') }}" alt="">
                                                        <span>Kết quả:</span>
                                                    </div>
                                                    <div class="col-7 text-center information_result">
                                                        <span>{{ !empty($get_result) && $get_result->result == 1 ? 'Hoàn thành' : 'Chưa hoàn thành' }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <img class="icon_class" src="{{ asset('images/account.png') }}" alt="">
                                                        <span>Tài khoản:</span>
                                                    </div>
                                                    <div class="col-7 text-center information_name">
                                                        <span>{{ !empty($check_register) && $check_register->status == 1 ? $profile->lastname . ' ' . $profile->firstname : ''}}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <img class="icon_class" src="{{ asset('images/last_access.png') }}" alt="">
                                                        <span>Ngày tham gia:</span>
                                                    </div>
                                                    <div class="col-7 text-center information_access">
                                                        <span>{{ !empty($date_join) ? date("d-m-Y H:i", strtotime($date_join->created_at)) : '' }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-md-12 mt-3">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th scope="col">Tên nội dung</th>
                                                <th scope="col">Điều kiện hoàn thành</th>
                                                <th scope="col">Tổng số lượt xem</th>
                                                <th scope="col">Kết quả</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($get_activity_courses as $get_activity_course)
                                                @php
                                                    $count_total_view = \Modules\Online\Entities\OnlineCourseActivityHistory::where('course_id',$get_activity_course->course_id)->where('course_activity_id',$get_activity_course->id)->count();
                                                    $get_activity_completion = Modules\Online\Entities\OnlineCourseActivityCompletion::where('course_id',$get_activity_course->course_id)->where('activity_id',$get_activity_course->id)->where('user_id', $profile->user_id)->where('user_type', 1)->first();
                                                @endphp
                                                <tr>
                                                    <th>
                                                        @if($get_activity_course->activity_id == 1)
                                                            <i class="uil uil-suitcase-alt crse_icon"></i>
                                                            <span class="section-title-text">{{ $get_activity_course->name }} </span>
                                                        @elseif ($get_activity_course->activity_id == 2)
                                                            <i class="uil uil-file-check crse_icon"></i>
                                                            <span class="section-title-text">{{ $get_activity_course->name }}</span>
                                                        @elseif ($get_activity_course->activity_id == 3)
                                                            <i class="uil uil-file crse_icon"></i>
                                                            <span class="section-title-text">{{ $get_activity_course->name }}</span>
                                                        @elseif ($get_activity_course->activity_id == 4)
                                                            <i class="uil uil-link crse_icon"></i>
                                                            <span class="section-title-text">{{ $get_activity_course->name }}</span>
                                                        @elseif ($get_activity_course->activity_id == 5)
                                                            <i class="uil uil-video crse_icon"></i>
                                                            <span class="section-title-text">{{ $get_activity_course->name }}</span>
                                                        @endif
                                                    </th>
                                                    <td class="text-center">
                                                        {{ !empty($condition_activity) && in_array($get_activity_course->id, $condition_activity) ? 'Có' : 'Không' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $count_total_view ? $count_total_view : '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $get_activity_completion && $get_activity_completion->status == 1 ? 'Hoàn thành' : 'Chưa hoàn thành' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade my-3" id="nav-ask-answer" role="tabpanel">
                            @livewire('online.ask-answer', ['course_id' => $item->id])
                        </div>

                        <div class="tab-pane fade show active" id="nav-courses" role="tabpanel">
                            @php
                                $status = $item->getStatusRegister();
                                $text = status_register_text($status);
                            @endphp
                            <div class="col-12">
                                <div class="row mt-2">
                                    <div class="col-12 col-md-3 p-0 collapse show" id="navbarToggleExternalContent">
                                        <div class="content_left accordion" id="accordion_table">
                                            @foreach ($lessons_course as $lessons_key => $lesson_course)
                                                <div class="card">
                                                    <div class="card-header p-1" id="heading-{{$lesson_course->id}}">
                                                        <h5 class="mb-0">
                                                            <button class="{{$lesson_course->id == $activeLession ? '' : 'collapsed'}} w-100 border-0 text-left p-2 lesson-title" data-toggle="collapse" data-target="#collapse-{{$lesson_course->id}}" aria-expanded="{{ $lesson_course->id == $activeLession ? true : false }}" aria-controls="collapse-{{$lesson_course->id}}">
                                                                <i class="fa fa-minus-square" aria-hidden="true"></i> <b>{{ $lesson_course->lesson_name }}</b>
                                                            </button>
                                                        </h5>
                                                    </div>

                                                    <div id="collapse-{{$lesson_course->id}}" class="collapse {{$lesson_course->id == $activeLession ? 'show' : ''}}" aria-labelledby="heading-{{$lesson_course->id}}" data-parent="#accordion_table">
                                                        <div class="card-body p-1">
                                                            @php
                                                                $activities = $item->getActivitiesOfLesson($lesson_course->id);
                                                                $activity_scorm = '';
                                                            @endphp
                                                            @foreach($activities as $key => $activity)
                                                                @php
                                                                    $bbb = \Modules\VirtualClassroom\Entities\VirtualClassroom::find($activity->subject_id);
                                                                    $parts = $activity->subject_id;
                                                                    $check_setting_activity = $activity->checkSettingActivity();
                                                                @endphp
                                                                <div class="row m-0 activityItem activityItemActive {{$activity->subject_id == $id_activity_scorm ? 'line-active' : ''}} {{ $key > 0 ? 'border-top' : '' }}">
                                                                    <div class="col-2 p-0">
                                                                    <span class="opts_account_course">
                                                                        @if($activity->isComplete(getUserId(), getUserType()))
                                                                            <img src="{{ asset('themes/mobile/img/check.png') }}" class="h-auto">
                                                                        @else
                                                                            <img src="{{ asset('themes/mobile/img/circle.png') }}" class="h-auto">
                                                                        @endif
                                                                    </span>
                                                                    </div>
                                                                    <div class="col-10 pl-0 pr-1">
                                                                    <span class="activityItem">
                                                                        @if($status == 4)
                                                                            @if($activity->activity_id == 1)
                                                                                @php
                                                                                    $get_course_activity = \Modules\Online\Entities\OnlineCourseActivity::findOrFail($activity->id);
                                                                                    $activity_scorm = \Modules\Online\Entities\OnlineCourseActivityScorm::findOrFail($get_course_activity->subject_id);
                                                                                @endphp
                                                                                <span @if($check_setting_activity)
                                                                                      onclick="activityCourse({{$item->id}},{{$activity->id}},{{$lesson_course->id}},1)"
                                                                                      @endif class="" data-turbolinks="false">
                                                                                    {{ $activity->name }}
                                                                                </span>
                                                                            @endif

                                                                            @if($activity->activity_id == 2)
                                                                                @if(is_null($parts))
                                                                                    <span>
                                                                                        {{ $activity->name .' ('. data_locale('Kỳ thi đã kết thúc hoặc Bạn chưa đăng kí kỳ thi', 'The exam has ended or You have not registered for it')  .')' }}
                                                                                    </span>
                                                                                @elseif(isset($parts) && !empty($parts->start_date) > date('Y-m-d H:i:s'))
                                                                                    <span>
                                                                                        {{ $activity->name .' ('. data_locale('Kỳ thi chưa tới giờ', 'Quiz is not yet time') .')' }}
                                                                                    </span>
                                                                                @else
                                                                                    <span @if($check_setting_activity)
                                                                                          onclick="activityCourse({{$item->id}},{{$activity->id}},{{$lesson_course->id}},2)"
                                                                                          @endif class="" data-turbolinks="false">
                                                                                       {{ $activity->name }}
                                                                                    </span>
                                                                                @endif
                                                                            @endif

                                                                            @if($activity->activity_id == 3)
                                                                                <span @if($check_setting_activity)
                                                                                      onclick="activityCourse({{$item->id}},{{$activity->id}},{{$lesson_course->id}},3)"
                                                                                      @endif class="" data-turbolinks="false">
                                                                                    {{ $activity->name }}
                                                                                </span>
                                                                            @endif

                                                                            @if($activity->activity_id == 4)
                                                                                <span @if($check_setting_activity)
                                                                                      onclick="activityCourse({{$item->id}},{{$activity->id}},{{$lesson_course->id}},4)"
                                                                                      @endif class="" data-turbolinks="false">
                                                                                    {{ $activity->name }}
                                                                                </span>
                                                                            @endif

                                                                            @if($activity->activity_id == 5)
                                                                                <span @if($check_setting_activity)
                                                                                      onclick="activityCourse({{$item->id}},{{$activity->id}},{{$lesson_course->id}},5)"
                                                                                      @endif class="" data-turbolinks="false">
                                                                                    {{ $activity->name }}
                                                                                </span>
                                                                            @endif

                                                                            @if($activity->activity_id == 6 && isset($bbb))
                                                                                @if($bbb->start_date > date('Y-m-d H:i:s'))
                                                                                    <span>
                                                                                        {{ $activity->name .' ('. data_locale('Lớp học chưa tới giờ', 'Class is not yet time') .')' }}
                                                                                    </span>
                                                                                @elseif($bbb->end_date < date('Y-m-d H:i:s'))
                                                                                    <span>
                                                                                        {{ $activity->name .' ('. data_locale('Lớp học đã kết thúc', 'Class has ended') .')' }}
                                                                                    </span>
                                                                                @else
                                                                                    <a href="javascript:void(0)" class="@if($check_setting_activity) go-bbb @endif" data-turbolinks="false" @if($check_setting_activity) data-url="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}" @endif>
                                                                                        {{ $activity->name }}
                                                                                    </a>
                                                                                @endif
                                                                            @endif
                                                                        @else
                                                                            <span class="activityItem">{{ $activity->name }}</span>
                                                                        @endif
                                                                    </span>
                                                                    </div>

                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-9 p-0 iframe_activity">
                                        <div class="row zoom_out m-2 float-right">
                                            <button type="button" class="btn btn-primary" id="zoom_out" onclick='zoomOut();'>
                                                <h5><i class="fa fa-search-minus" style="font-size:16px"></i></h5>
                                            </button>
                                            <button type="button" class="btn btn-primary" id="zoom_in" onclick='fullScreen();'>
                                                <h5><i class="fa fa-search-plus" style="font-size:16px"></i></h5>
                                            </button>
                                        </div>
                                        <div id="carouselCourse" class="carousel slide w-100" data-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class="carousel-item h-100 active" style="background:url({{ $item->image_activity ? image_file($item->image_activity) : '' }}) no-repeat center; background-size:cover"></div>
                                            </div>
                                            <div class="start_course">
                                                @if (!empty($check_register) && $check_register->status == 1)
                                                    <button class="btn btn-primary button_start" onclick="startCourse()">
                                                        <h4>{{ $check_activity_active == 0 ? 'Xin mời Anh/Chị bắt đầu vào khóa học' : 'Xin mời Anh/Chị tiếp tục khóa học' }}</h4>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- <iframe src="{{ ($type_activity == 0 || $type_activity == 2) && !empty($check_register) && $check_register->status == 1 ? $link : ''}}" class="iframe-embed w-100" id="iframe-embed-url" allowfullscreen="allowfullscreen" scrolling="auto" onload="access()"></iframe> --}}
                                        <iframe src="" class="iframe-embed w-100" id="iframe-embed-url" allowfullscreen="allowfullscreen" onload="access()" scrolling="auto"></iframe>
                                        <input type="hidden" name="type_activity" value="{{ $type_activity }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade my-3" id="nav-reviews" role="tabpanel">
                            @livewire('online.comment', ['course_id' => $item->id,'avg_star' => $item->avgRatingStar()])
                        </div>

                        <div class="tab-pane fade my-3" id="nav-note" role="tabpanel">
                            @livewire('online.note', ['course_id' => $item->id])
                        </div>

                        <div class="tab-pane fade my-3" id="nav-document" role="tabpanel">
                            @if($item->document)
                                <a href="{{ $item->getLinkDownload() }}"
                                   class="btn btn_adcart"
                                   target="_blank">
                                    <i class="fa fa-download"></i> @lang('app.download')
                                </a>
                            @endif

                            @if($item->isFilePdf())
                                <a href="{{ route('module.online.view_pdf', ['id' => $item->id]) }}"
                                   target="_blank"
                                   class="btn btn_adcart click-view-doc"
                                   data-id="{{$item->id}}" >
                                    <i class="fa fa-eye"></i> @lang('app.watch_online')
                                </a>
                            @endif

                        </div>

                        <div class="tab-pane fade my-3" id="nav-rating-level" role="tabpanel">
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

                        <div class="tab-pane fade my-3" id="nav-history-learning" role="tabpanel">
                            <div class="col-12 mt-2">
                                <div class="row">
                                    <div class="col-12 col-md-4 mt-2">
                                        @foreach ($get_activity_courses as $key_history => $get_activity_quiz_scorm)
                                            @if ($get_activity_quiz_scorm->activity_id == 1)
                                                @php
                                                    $activity_history_scorm = \Modules\Online\Entities\OnlineCourseActivityScorm::findOrFail($get_activity_quiz_scorm->subject_id);
                                                @endphp
                                                <div class="history_name" onclick="opend_history_scorm({{ $activity_history_scorm->id }}, {{ $key_history }})">
                                                    <span>{{ $get_activity_quiz_scorm->name }} </span>
                                                    <i class="fas fa-caret-right float-right"></i>
                                                </div>
                                            @elseif ($get_activity_quiz_scorm->activity_id == 2)
                                                @php
                                                    $user_type = getUserType();
                                                    $user_id = getUserId();
                                                    $part =  \Modules\Quiz\Entities\QuizPart::where('quiz_id', '=', $get_activity_quiz_scorm->subject_id)
                                                    ->whereIn('id', function ($subquery) use ($user_id, $user_type, $get_activity_quiz_scorm) {
                                                        $subquery->select(['a.part_id'])
                                                            ->from('el_quiz_register AS a')
                                                            ->join('el_quiz_part AS b', 'b.id', '=', 'a.part_id')
                                                            ->where('a.quiz_id', '=', $get_activity_quiz_scorm->subject_id)
                                                            ->where('a.user_id', '=', $user_id)
                                                            ->where('a.type', '=', $user_type)
                                                            ->where(function ($where){
                                                                $where->orWhere('b.end_date', '>', date('Y-m-d H:i:s'));
                                                                $where->orWhereNull('b.end_date');
                                                            });
                                                    })->first();
                                                @endphp
                                                <div class="history_name" onclick="opend_history_quiz({{ $get_activity_quiz_scorm->subject_id}}, {{ !empty($part) ? $part->id : 0 }}, {{ $key_history }})">
                                                    <span>{{ $get_activity_quiz_scorm->name }} </span>
                                                    <i class="fas fa-caret-right float-right"></i>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="col-12 col-md-8 mt-3">
                                        @foreach ($get_activity_courses as $key_history => $get_activity_quiz_scorm)
                                            @if ($get_activity_quiz_scorm->activity_id == 1)
                                                <div class="table_history" id="table_history_{{$key_history}}">
                                                    <table class="tDefault table table-hover bootstrap-table table-bordered" id="table-history-scrom-{{$get_activity_quiz_scorm->subject_id}}">
                                                        <thead>
                                                        <tr>
                                                            <th data-formatter="index_formatter" data-align="center">#</th>
                                                            <th data-field="start_date">{{ trans('app.start_date') }}</th>
                                                            <th data-field="end_date">{{ trans('app.end_date') }}</th>
                                                            <th data-field="grade" data-align="center">{{ trans('app.score') }}</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @elseif ($get_activity_quiz_scorm->activity_id == 2)
                                                <div class="table_history" id="table_history_{{$key_history}}">
                                                    <table class="tDefault table table-hover bootstrap-table table-bordered" id="table-history-quiz-{{$get_activity_quiz_scorm->subject_id}}">
                                                        <thead>
                                                        <tr>
                                                            <th data-formatter="index_formatter" data-align="center">#</th>
                                                            <th data-field="start_date">{{ trans('app.start_date') }}</th>
                                                            <th data-field="end_date">{{ trans('app.end_date') }}</th>
                                                            <th data-field="grade" data-align="center">{{ trans('app.score') }}</th>
                                                            <th data-field="status" data-align="center">{{ trans('app.status') }}</th>
                                                            <th data-field="review" data-align="center" data-formatter="review_formatter">{{ trans('app.review') }}</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
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
    </div>
    <script>
        window.Rating = {
            route: '{{ route('module.online.rating',$item->id) }}',
        };
        var rating = $('.rating');
        ratingStars(rating);

        var open = false;
        var loaded = false;
        var url_link = "{{ route('module.online.scorm.play', [$item->id, ':id']) }}";
        //BẮT ĐẦU/ TIẾP TỤC KHÓA HỌC
        $('#iframe-embed-url').css('display','none');
        $('#carouselCourse').css('display','block');
        function startCourse() {
            var check_type_activity = '<?php echo $type_activity ?>';
            if (check_type_activity == '1') {
                var get_link = '<?php echo $id_activity_scorm ?>';
                $(document).ready(function() {
                    runLastScrom(get_link);
                });
            } else if (check_type_activity == '2') {
                var link = '<?php echo $link ?>';
                $('#iframe-embed-url').attr('src', link);
            } else {
                var link = '<?php echo $link ?>';
                $('#iframe-embed-url').attr('src', link)
            }
            $('#iframe-embed-url').css('display','block');
            $('#carouselCourse').css('display','none');
            open = true;
        }

        // GỌI CÁC HOẠT ĐỘNG
        function activityCourse(id,aid,lesson_id,type) {
            if(open) {
                document.querySelector('.activityItemActive').style.pointerEvents = 'none';
                $('#iframe-embed-url').css('display','block');
                $('#carouselCourse').css('display','none');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('module.online.detail.ajax_activity') }}',
                    dataType: 'json',
                    data: {
                        'id': id,
                        'aid': aid,
                        'lesson_id': lesson_id,
                        'type': type,
                    }
                }).done(function(data) {
                    $('input[name="type_activity"]').val(type);
                    var el = document.getElementById('iframe-embed-url');
                    el.src = '';
                    if (data) {
                        setTimeout(function(){
                            if (type !== 1) {
                                el.src = data.link;
                            } else if (type == 2) {
                                el.src = data.link;
                            } else {
                                let $activity_id =  data.link.id;
                                playScrom($activity_id);
                            }
                            var iframeDoc = el.contentDocument || el.contentWindow.document;
                            if (  iframeDoc.readyState  == 'complete' ) {
                                document.querySelector('.activityItemActive').style.pointerEvents = 'auto';
                            }
                        },700);
                    }
                    return false;
                }).fail(function(data) {
                    show_message("{{ trans('lageneral.data_error ') }}", 'error');
                    return false;
                });
            }
        }

        //CHẠY GÓI SCROM LẦN CUỐI ĐĂNG NHẬP
        function runLastScrom($activity_id) {
            if (!loaded) {
                loaded = true;
                let url = url_link.replace(':id',$activity_id);
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: 'json',
                    data: {},
                    success: function (result) {
                        if (result.status == "success") {
                            var el = document.getElementById('iframe-embed-url');
                            el.src = result.redirect;
                            return false;
                        }
                        show_message(result.message, result.status);
                        return false;
                    }
                });
            }
        }

        // ẨN LỊCH SỬ KỲ THI IFRAME/ SET KÍCH THƯỚC SCROM
        function access() {
            setTimeout(function(){
                var type = $('input[name="type_activity"]').val();
                var iframe = document.getElementById("iframe-embed-url");
                if(type == 2) {
                    var elmnt = iframe.contentWindow.document.getElementById("history_quiz");
                    var card_header_quiz = iframe.contentWindow.document.getElementById("card_header_quiz");
                    var info_user = iframe.contentWindow.document.querySelector(".info_user");
                    var footer_body = iframe.contentWindow.document.querySelector("#footer_body");
                    if(elmnt && card_header_quiz && info_user) {
                        elmnt.style.display = "none";
                        card_header_quiz.style.display = "none";
                        info_user.style.display = "none";
                    }

                    if(footer_body) {
                        footer_body.style.display = "none";
                    }
                } else {
                    var innerDoc1 = iframe.contentDocument || iframe.contentWindow.document;
                    var iframe2 = innerDoc1.getElementById('scorm_object');
                    if(iframe2) {
                        var innerDoc2 = iframe2.contentDocument || iframe2.contentWindow.document;
                        var message_window_slide = innerDoc2.querySelector("#message-window-slide");
                        var message_window_wrapper = innerDoc2.querySelector("#message-window-wrapper");
                        var message_window_heading = innerDoc2.querySelector(".message-window-heading");
                        if(message_window_slide && message_window_wrapper){
                            message_window_slide.style.height = 'auto';
                            message_window_wrapper.style.height = 'auto';
                            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
                                message_window_heading.style.fontSize = '58%';
                                message_window_heading.style.setProperty('padding', '7px', 'important');;
                            }
                        }
                    }
                }
            },300);
        }

        //MỞ GÓI SCORM
        function playScrom($activity_id) {
            let url = url_link.replace(':id',$activity_id);
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: {},
                success: function (result) {
                    if (result.status == "success") {
                        var el = document.getElementById('iframe-embed-url');
                        el.src = result.redirect;
                        return false;
                    }
                    show_message(result.message, result.status);
                    return false;
                }
            });
        }

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        $('.table_history').hide();
        // MỞ LỊCH SỬ SCORM
        function opend_history_scorm(id, key) {
            $('.table_history').hide();
            $('#table_history_'+key).show();
            var url = "{{ route('module.online.attempts', ':id') }}";
            url = url.replace(':id',id);
            var table_scrom = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: url,
                table: '#table-history-scrom-'+id
            });
        }

        // MỞ LỊCH SỬ KỲ THÌ
        function opend_history_quiz(quizId, partId, key) {
            $('.table_history').hide();
            $('#table_history_'+key).show();
            var url = "{{ route('module.quiz.doquiz.attempt_history', ['quiz_id' => ':id', 'part_id' => ':partId']) }}";
            url = url.replace(':id',quizId);
            url = url.replace(':partId',partId);
            var table = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: url,
                table: '#table-history-quiz-'+quizId
            });
        }

        function review_formatter(value, row, index) {
            if (row.after_review == 1 || row.closed_review == 1) {
                return '<a href="'+ row.review_link +'">Xem lại</a>'
            }
            return '<span class="text-muted">Không được xem</span>';
        }

        //PHÓNG TO
        $('#zoom_out').hide();
        function fullScreen(){
            var heightPage = document.body.clientHeight;
            console.log(heightPage);
            $('.content_left').hide();
            if($('#nav-courses').find('.iframe_activity').hasClass('col-md-9')){
                $('#nav-courses').find('.iframe_activity').removeClass('col-md-9');
            }
            /*$('.iframe_activity').addClass('col-md-12');*/
            $('#zoom_out').show();
            $('#zoom_in').hide();
            $('.header_bar').hide();
            $('.nav_tab_menu').hide();
            $('.body_content_detail').removeClass('container-fluid');
            $('.iframe_activity').css('min-height',heightPage);
            $('.iframe-embed').css('min-height',heightPage);
            access();
        }

        //THU NHỎ
        function zoomOut() {
            $('.content_left').show();
            /*$('.iframe_activity').removeClass('col-md-12');*/
            if($('#nav-courses').find('#navbarToggleExternalContent').hasClass('show')){
                $('#nav-courses').find('.iframe_activity').addClass('col-md-9');
            }
            /*$('.iframe_activity').addClass('col-md-9');*/
            $('#zoom_out').hide();
            $('#zoom_in').show();
            $('.header_bar').show();
            $('.nav_tab_menu').show();
            $('.body_content_detail').addClass('container-fluid');
            $('.iframe_activity').css('min-height','600px')
            $('.iframe-embed').css('min-height','525px')
            access();
        }

        function rating_url_formatter(value, row, index) {
            if(row.rating_level_url){
                return '<a href="'+ row.rating_level_url +'" class="btn btn-info">Đánh giá</a>';
            }
            return 'Đánh giá';
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.detail.rating_level.getdata', ['id' => $item->id]) }}',
            table: '#table-rating-level',
        });

        $('#btnMenuListActivity').on('click', function () {
            if($('#nav-courses').find('.iframe_activity').hasClass('col-md-9')){
                $('#nav-courses').find('.iframe_activity').removeClass('col-md-9');
            }else{
                $('#nav-courses').find('.iframe_activity').addClass('col-md-9');
            }
            access();
        });

        $('#nav-courses').on('click', '.activityItemActive', function () {
            if(open) {
                if($('#accordion_table').find('.activityItemActive').hasClass('line-active')){
                    $('#accordion_table').find('.activityItemActive').removeClass('line-active')
                }

                $(this).addClass('line-active');
            }
        });
    </script>
@endsection
