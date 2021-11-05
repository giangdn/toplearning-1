@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.onl_course'))

@section('content')
    <div class="container pl-0 pr-0" id="detail-online">
        <div class="card shadow border-0 bg-template mb-2 mt-2">
            <div class="card-body">
                <div class="row">
                    @php
                        $status = \App\Profile::usertype() != 2 ? $item->getStatusRegister() : 4;
                        $text = \Modules\Online\Entities\OnlineCourse::getStatusRegisterText($status);
                        $percent = \Modules\Online\Entities\OnlineCourse::percentCompleteCourseByUser($item->id, \Auth::id());
                    @endphp
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 align-self-center block-image p-1">
                        <img src="{{ image_file($item->image) }}" alt="" class="w-100 thumbnail-image picture_course" id="thumbnail-image-course" style="object-fit: cover">

                        <iframe src="" class="iframe-embed w-100" id="iframe-embed-url" allowfullscreen="allowfullscreen" scrolling="auto" onload="access()" style="display: none;"></iframe>

                        <div id="quiz-iframe"></div>
                    </div>

                    <div class="col-12 p-3 col-sm-6 col-md-6 col-lg-6 col-xl-6 align-self-center text-white p-1" id="info-course-detail">
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
                            <b>@lang('app.training_process')</b>
                            <div class="progress progress2 bg-white" style="border-radius: 10px;">
                                <div class="progress-bar w-70" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                    {{ round($percent, 2) }}%
                                </div>
                            </div>
                        <b>@lang('app.register_deadline'):</b> {{ get_date($item->register_deadline) }} <br>

                        <p><b>@lang('app.trainee_object'):</b> {{ $item->checkObject() ? trans('app.you_belong_course') : trans('app.you_not_on_course') }}</p>

                        @if($status == 1)
                            <form action="{{ route('module.online.register_course', ['id' => $item->id]) }}" method="post" class="form-ajax">
                                @csrf
                                <div class="item item-btn">
                                    <button type="submit" class="btn btn-primary">{{ $text }}</button>
                                </div>
                            </form>

                        @elseif($status == 4)
                            {{-- <button href="javascript:void(0)" class="btn btn-info" id="go-course">{{ mb_strtoupper($text) }}</button> --}}
                            {{-- <div id="notify-course" class="bold font-weight-bold text-justify"> --}}
                                <i class="material-icons vm">feedback</i> @lang('app.notify_go_course')
                            {{-- </div>  --}}
                        @else
                            <button type="button" class="btn btn-danger">{{ $text }}</button>
                        @endif

                        {{--@if($item->rating)
                            @if($item->rating_end_date && $item->rating_end_date < date('Y-m-d H:i:s'))
                                <div class="mt-2">
                                    Đã kết thúc thời gian đánh giá sau khóa học
                                </div>
                            @else
                            <div class="mt-2">
                                <a href="{{ isset($rating_course) ? route('module.rating.edit_course', ['type' => 1, 'id' => $item->id]) : route('module.rating.course', ['type' => 1, 'id' => $item->id]) }}" class="btn btn-info text-white"> Đánh giá sau khóa học</a>
                            </div>
                            @endif
                        @endif--}}

                    </div>
                </div>
            </div>
        </div>

        <div class="row item-activity m-0">
            <div class="col-lg-12 p-0">
                <div class="course_tabs">
                    <div class="col-12 px-0">
                        <div class="swiper-container online-course-slide">
                            <div class="swiper-wrapper nav-pills mb-2 text-center" id="nav-tab" role="tablist">
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-selected="true">
                                    @lang('app.description')
                                </a>
                                <a class="swiper-slide nav-item nav-link active pl-0 pr-0" id="nav-courses-tab" data-toggle="tab" href="#nav-courses" role="tab" aria-selected="true">
                                    @lang('app.content')
                                </a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-result-tab" data-toggle="tab" href="#nav-result" role="tab" aria-selected="true">
                                    {{ data_locale('Kết quả', 'Result') }}
                                </a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-ask-answer-tab" data-toggle="tab" href="#nav-ask-answer" role="tab" aria-selected="true">
                                    {{ data_locale('Hỏi & đáp', 'Q & A') }}
                                </a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-document-tab" data-toggle="tab" href="#nav-document" role="tab" aria-selected="true">
                                    {{ data_locale('Tài liệu', 'References') }}
                                </a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-reviews-tab" data-toggle="tab" href="#nav-reviews" role="tab" aria-selected="true">
                                    @lang('app.comment')
                                </a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-note-tab" data-toggle="tab" href="#nav-note" role="tab" aria-selected="true">
                                    {{ data_locale('Ghi chép', 'Note') }}
                                </a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-rating-level-tab" data-toggle="tab" href="#nav-rating-level" role="tab" aria-selected="true">
                                    {{ data_locale('Đánh giá đào tạo', 'Evaluate') }}
                                </a>
                                <a class="swiper-slide nav-item nav-link pl-0 pr-0" id="nav-history-learning-tab" data-toggle="tab" href="#nav-history-learning" role="tab" aria-selected="true">
                                    {{ data_locale('Lịch sử học tập', 'Learning History') }}
                                </a>
                            </div>
                        </div>
                    </div>
                   {{-- <nav>
                        <div class="nav nav-pills mb-4 tab_crse justify-content-center" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link " id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab"
                               aria-selected="true">@lang('app.description')</a>
                            <a class="nav-item nav-link active" id="nav-courses-tab" data-toggle="tab" href="#nav-courses" role="tab"
                               aria-selected="false"> @lang('app.content')</a>
                            <a class="nav-item nav-link" id="nav-reviews-tab" data-toggle="tab" href="#nav-reviews" role="tab"
                               aria-selected="false">@lang('app.comment')</a>
                            <a class="nav-item nav-link" id="nav-history-learning-tab" data-toggle="tab" href="#nav-history-learning" role="tab" aria-selected="false">Lịch sử học tập</a>
                        </div>
                    </nav>--}}
                </div>
            </div>
        </div>
        <div class="row item-activity m-0">
            <div class="col-lg-12 p-0">
                <div class="course_tab_content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade" id="nav-about" role="tabpanel">
                            <div class="text-justify">
                                {!! $item->content !!}
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="nav-courses" role="tabpanel">
                            <div class="crse_content">
                                <div class="col-12">
                                    @foreach ($lessons_course as $key => $lesson_course)
                                        @php
                                            $activities = $item->getActivitiesOfLesson($lesson_course->id);
                                            $activity_scorm = '';
                                        @endphp
                                        @foreach($activities as $activity)
                                            @php
                                                $parts = \Modules\Quiz\Entities\QuizPart::checkQuizPartOnline($activity->subject_id);
                                                $checked = $activity->isComplete(\Auth::id());
                                            @endphp
                                            @if($status == 4)
                                                @if (($activity->activity_id != 2 && userThird()) || !userThird())
                                                <div class="row mb-2">
                                                    <div class="col-10">
                                                        @if(is_null($parts) && $activity->activity_id == 2)
                                                            {{ $activity->name }} <br> {{ '('. data_locale('Đã kết thúc hoặc Chưa đăng kí', 'Has ended or Not registered') .')' }}
                                                        @else
                                                            @if(isset($parts) && $parts->start_date > date('Y-m-d H:i:s'))
                                                                {{ $activity->name .' ('. data_locale('Kỳ thi chưa tới ngày', 'Less exam day') .')' }}
                                                            @elseif ($activity->activity_id == 1)
                                                                <span onclick="activityCourse({{$item->id}},{{$activity->id}},{{$lesson_course->id}},1)" class="" data-turbolinks="false">
                                                                    {{ $activity->name }}
                                                                </span>
                                                            @elseif ($activity->activity_id == 2)
                                                                <span onclick="activityCourse({{$item->id}},{{$activity->id}},{{$lesson_course->id}},2)" class="" data-turbolinks="false">
                                                                    {{ $activity->name }}
                                                                </span>
                                                            @elseif ($activity->activity_id == 5)
                                                                <span onclick="activityCourse({{$item->id}},{{$activity->id}},{{$lesson_course->id}},5)" class="" data-turbolinks="false">
                                                                    {{ $activity->name }}
                                                                </span>
                                                            @else
                                                                <a href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}" class="{{ ($activity->activity_id == 3) ? 'active_file' : (($activity->activity_id == 4) ? 'active_url' : '') }}">
                                                                    <span class="instancename">{{ $activity->name }} </span>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>

                                                    <div class="col-2 float-right">
                                                        @if($checked)
                                                            <img src="{{ asset('themes/mobile/img/check.png') }}" class="avatar avatar-20">
                                                        @else
                                                            <img src="{{ asset('themes/mobile/img/circle.png') }}" class="avatar avatar-20">
                                                        @endif
                                                    </div>
                                                </div>
                                                @endif
                                            @else
                                                <div class="row mb-2">
                                                    <div class="col-10">
                                                        <span class="instancename">{{ $activity->name }} </span>
                                                    </div>
                                                    <div class="col-2 float-right">
                                                        @if($checked)
                                                            <img src="{{ asset('themes/mobile/img/check.png') }}" class="avatar avatar-20">
                                                        @else
                                                            <img src="{{ asset('themes/mobile/img/circle.png') }}" class="avatar avatar-20">
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-result" role="tabpanel">
                            <div class="my-3">
                                <div class="row m-0">
                                    <div class="col-12">
                                        <h6 class="p-1">Thông tin</h6>
                                    </div>
                                    <div class="col-12">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5 pl-0">
                                                        <img class="icon_class" src="{{ asset('images/class.png') }}" alt="">
                                                        <span>Lớp học</span>
                                                    </div>
                                                    <div class="col-7 text-center information_name">
                                                        <span>{{ $item->name }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5 pl-0">
                                                        <img class="icon_class" src="{{ asset('images/last_access.png') }}" alt="">
                                                        <span>Lần truy cập gần nhất</span>
                                                    </div>
                                                    <div class="col-7 text-center information_access">
                                                        <span>{{ date("d-m-Y H:i", strtotime($time_user_view_course->time_view)) }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5 pl-0">
                                                        <img class="icon_class" src="{{ asset('images/score.png') }}" alt="">
                                                        <span>Điểm tổng kết</span>
                                                    </div>
                                                    <div class="col-7 text-center information_score">
                                                        <span>{{ !empty($get_result) ? $get_result->score : '0' }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5 pl-0">
                                                        <img class="icon_class" src="{{ asset('images/result.png') }}" alt="">
                                                        <span>Kết quả</span>
                                                    </div>
                                                    <div class="col-7 text-center information_result">
                                                        <span>{{ !empty($get_result) && $get_result->result == 1 ? 'Hoàn thành' : 'Chưa hoàn thành' }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5 pl-0">
                                                        <img class="icon_class" src="{{ asset('images/account.png') }}" alt="">
                                                        <span>Tài khoản</span>
                                                    </div>
                                                    <div class="col-7 text-center information_name">
                                                        <span>{{ !empty($check_register) && $check_register->status == 1 ? $profile->lastname . ' ' . $profile->firstname : ''}}</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item">
                                                <div class="row">
                                                    <div class="col-5 pl-0">
                                                        <img class="icon_class" src="{{ asset('images/last_access.png') }}" alt="">
                                                        <span>Ngày tham gia</span>
                                                    </div>
                                                    <div class="col-7 text-center information_access">
                                                        <span>{{ !empty($date_join) ? date("d-m-Y H:i", strtotime($date_join->created_at)) : '' }}</span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <table class="table table-striped">
                                            <tbody>
                                            @foreach ($get_activity_courses as $get_activity_course)
                                                @php
                                                    $count_total_view = \Modules\Online\Entities\OnlineCourseActivityHistory::where('course_id',$get_activity_course->course_id)->where('course_activity_id',$get_activity_course->id)->count();
                                                    $get_activity_completion = Modules\Online\Entities\OnlineCourseActivityCompletion::where('course_id',$get_activity_course->course_id)->where('activity_id',$get_activity_course->id)->first();
                                                @endphp
                                                <tr>
                                                    <th>
                                                        Nội dung:
                                                        @if($get_activity_course->activity_id == 1)
                                                            {{ $get_activity_course->name }}
                                                        @elseif ($get_activity_course->activity_id == 2)
                                                            {{ $get_activity_course->name }}
                                                        @elseif ($get_activity_course->activity_id == 3)
                                                            {{ $get_activity_course->name }}
                                                        @elseif ($get_activity_course->activity_id == 4)
                                                            {{ $get_activity_course->name }}
                                                        @elseif ($get_activity_course->activity_id == 5)
                                                            {{ $get_activity_course->name }}
                                                        @endif
                                                            <br>
                                                        Điều kiện hoàn thành: {{ !empty($condition_activity) && in_array($get_activity_course->id, $condition_activity) ? 'Có' : 'Không' }}
                                                            <br>
                                                        Tổng số lượt xem: {{ $count_total_view ? $count_total_view : '' }}
                                                            <br>
                                                        Kết quả: {{ $get_activity_completion && $get_activity_completion->status == 1 ? 'Hoàn thành' : 'Chưa hoàn thành' }}
                                                    </th>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-ask-answer" role="tabpanel">
                            @include('themes.mobile.frontend.online_course.ask_answer')
                        </div>
                        <div class="tab-pane fade" id="nav-document" role="tabpanel">
                            @if($item->document)
                                <a href="{{ $item->getLinkDownload() }}"
                                   class="btn btn_adcart text-white"
                                   target="_blank">
                                    <i class="fa fa-download"></i> @lang('app.download')
                                </a>
                            @endif

                            @if($item->isFilePdf())
                                <a href="{{ route('module.online.view_pdf', ['id' => $item->id]) }}"
                                   target=""
                                   class="btn btn_adcart click-view-doc text-white"
                                   data-id="{{$item->id}}" >
                                    <i class="fa fa-eye"></i> @lang('app.watch_online')
                                </a>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="nav-reviews" role="tabpanel">
                            @include('themes.mobile.frontend.online_course.comment')
                        </div>
                        <div class="tab-pane fade" id="nav-note" role="tabpanel">
                            @include('themes.mobile.frontend.online_course.note')
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
                        <div class="tab-pane fade my-3" id="nav-history-learning" role="tabpanel">
                            <div class="col-12 mt-2">
                                <div class="row m-0">
                                    <div class="col-12 mt-2">
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
                                    <div class="col-12 mt-3">
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
@endsection

@section('footer')
    <script type="text/javascript">
        var swiper = new Swiper('.online-course-slide', {
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
                localStorage.setItem('activeTab-online-course{{$item->id}}', $(e.target).attr('href'));
            });
            var activeTab = localStorage.getItem('activeTab-online-course{{$item->id}}');
            if(activeTab){
                $('a[data-toggle="tab"]').removeClass('active');
                $('#nav-tab a[href="' + activeTab + '"]').tab('show');
                $('#nav-tab a[href="' + activeTab + '"]').addClass('active');
            }
        });

        $('.nav-pills').on('click', '.nav-link', function() {
            $("html, body").animate({
                scrollTop: $('html, body').get(0).scrollHeight
            }, 300);
        });

        $('#notify-course').prop('hidden', true);

        $("#go-course").on('click', function () {
            $('a[data-toggle="tab"]').removeClass('active');
            $('#nav-tab a[href="#nav-courses"]').tab('show');
            $('#nav-tab a[href="#nav-courses"]').addClass('active');

            $('#go-course').prop('hidden', true);
            $('#notify-course').prop('hidden', false);

            $("html, body").animate({
                scrollTop: $('html, body').get(0).scrollHeight
            }, 300);
        });

        $('.active_file').on('click', function () {
            $(this).closest('.row').find('.file').prop('checked', true);
        });
        $('.active_url').on('click', function () {
            $(this).closest('.row').find('.url').prop('checked', true);
        });

        // GỌI HOẠT ĐỘNG SCROM
        $('#iframe-embed-url').css('display','none');
        $('#thumbnail-image-course').css('display','block');

        var url_link = "{{ route('module.online.scorm.play', [$item->id, ':id']) }}";
        function activityCourse(id,aid,lesson_id,type) {
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
                var el = document.getElementById('iframe-embed-url');
                el.src = '';
                if (data) {
                    setTimeout(function(){
                        $('#thumbnail-image-course').css('display','none');

                        if(type != 2){
                            $('#iframe-embed-url').css({'display':'block', 'height':'250px'});
                            $('#quiz-iframe').html('');
                        }
                    }, 500);

                    if (type == 1) {
                        url_link = url_link.replace(':id',data.link.id);
                        playScrom();
                    } else if(type == 2){
                        $('#iframe-embed-url').css('display','none');

                        $('#quiz-iframe').load(data.link);
                    } else{
                        el.src = data.link;

                        localStorage.setItem('activity-active', el.src);
                    }
                }
                return false;
            }).fail(function(data) {
                show_message("{{ data_locale('Lỗi dữ liệu', 'Data error') }}", 'error');
                return false;
            });
        }
        //MỞ GÓI SCORM
        function playScrom() {
            $.ajax({
                type: "POST",
                url: url_link,
                dataType: 'json',
                data: {},
                success: function (result) {
                    if (result.status == "success") {
                        //console.log(result.redirect);
                        var el = document.getElementById('iframe-embed-url');
                        el.src = result.redirect;

                        localStorage.setItem('activity-active', el.src);
                        /*window.location.href = result.redirect*/
                        return false;
                    }
                    show_message(result.message, result.status);
                    return false;
                }
            });
        }

        function access() {
            setTimeout(function(){
                var iframe = document.getElementById("iframe-embed-url");
                var innerDoc1 = iframe.contentDocument || iframe.contentWindow.document;
                var iframe2 = innerDoc1.getElementById('scorm_object');
                if(iframe2) {
                    var innerDoc2 = iframe2.contentDocument || iframe2.contentWindow.document;
                    var message_window_slide = innerDoc2.querySelector("#message-window-slide");
                    var message_window_wrapper = innerDoc2.querySelector("#message-window-wrapper");
                    var message_window_heading = innerDoc2.querySelector(".message-window-heading");
                    if(message_window_slide && message_window_wrapper){
                        message_window_slide.style.setProperty('height', 'auto', 'important');
                        message_window_wrapper.style.setProperty('height', 'auto', 'important');
                        message_window_heading.style.fontSize = '58%';
                        message_window_heading.style.setProperty('padding', '7px', 'important');
                        message_window_heading.style.setProperty('font-size', '58%', 'important');
                    }
                }
            },500);
        }

        // MỞ LỊCH SỬ SCORM
        $('.table_history').hide();
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

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function review_formatter(value, row, index) {
            if (row.after_review == 1 || row.closed_review == 1) {
                return '<a href="'+ row.review_link +'">Xem lại</a>'
            }
            return '<span class="text-muted">Không được xem</span>';
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

        function rating_url_formatter(value, row, index) {
            var btn_rating_level_url = '<a href="#" class="btn btn-info text-white">Không thể đánh giá</a>';
            if(row.rating_level_url){
                btn_rating_level_url = '<a href="'+ row.rating_level_url +'" class="btn btn-info text-white">Đánh giá</a>';
            }

            return '<b>'+ row.rating_name +'</b> <br>' + 'Thời gian: ' + row.rating_time + '<br>' + 'Trạng thái '+ row.rating_status + '<br>' + btn_rating_level_url;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.detail.rating_level.getdata', ['id' => $item->id]) }}',
            table: '#table-rating-level',
        });

        var max_with_page = $(window).width();
        var max_height_page = $(window).height();
        if (max_with_page == 800 || max_with_page == 780 || max_with_page == 851 || max_with_page == 1104 || max_with_page == 568 || max_with_page == 812 || max_with_page == 896 || max_with_page == 844 || max_with_page == 926 || max_with_page == 1024 || max_with_page == 1180 || max_with_page == 1280){
            var activityActive = localStorage.getItem('activity-active');
            if (activityActive){
                $('#thumbnail-image-course').css('display','none');
                $('#iframe-embed-url').css({'display':'block', 'height': '85vh'});

                var el = document.getElementById('iframe-embed-url');
                el.src = activityActive;
            }

            $('#homepage .header').hide();
            $('#homepage .footer').hide();
            $('#homepage #info-course-detail').hide();
            $('#homepage .item-activity').hide();
            $('#homepage .block-image').removeClass('col-sm-6 col-md-6 col-lg-6 col-xl-6');

            $('#detail-online').css({'padding-top':'0px', 'height': '100vh'});
            $('#detail-online .card').removeClass('mb-2 mt-2');
            $('#detail-online #thumbnail-image-course').removeClass('picture_course');
            $('#detail-online #thumbnail-image-course').css({'height': '85vh'});
            $('.wrapper').css('padding-bottom', '0px');
        }else{
            $('#homepage .header').show();
            $('#homepage .footer').show();
            $('#homepage #info-course-detail').show();
            $('#homepage .item-activity').show();
            $('#homepage .block-image').addClass('col-sm-6 col-md-6 col-lg-6 col-xl-6');

            $('#detail-online').css('padding-top','54px');
            $('#detail-online .card').addClass('mb-2 mt-2');
            $('#detail-online #thumbnail-image-course').addClass('picture_course');
            $('.wrapper').css('padding-bottom', '80px');
        }
    </script>
@endsection
