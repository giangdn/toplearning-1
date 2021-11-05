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
                            @lang('app.course')
                            <i class="uil uil-angle-right"></i>
                            @if(getUserType() == 1)
                                <span> @lang('app.onl_course')</span>
                            @else
                                <a href="{{ route('module.frontend.user.my_course', [0]) }}"> @lang('app.my_course')
                                </a>
                            @endif
                            <i class="uil uil-angle-right"></i>
                            <span class="font-weight-bold">{{ $item->name }}</span>
                        </h2>
                    </div>
                </div>
            </div>
            <p></p>
            <div class="row">
                <div class="col-lg-4 col-12">
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
                                                <div class="badge_seller">{{ $point }} <img class="point ml-1" style="width: 20px;height: 20px" src=" {{ asset('styles/images/level/point.png') }}" alt=""></div>
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
                                    <span href="javascript:void(0)" style="cursor: pointer" class="ml-2" id="share-course"><i class="fas fa-link"></i> Share</span>
                                </div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 detail">
                                <div class="_215b05">
                                    <h2>{{ $item->name }}</h2>
                                    <span class="_215b05">{{ \Illuminate\Support\Str::words($item->description, 20) }}</span>
                                </div>
                                <div class="_215b05">
                                    <div class="crse_reviews mr-2">
                                        <i class="uil uil-star"></i>{{ $item->avgRatingStar() }}
                                    </div>
                                    ({{ $item->countRatingStar() }} @lang('app.votes'))
                                    @if (!empty($item->tutorial))
                                        <b id="tutorial_course" class="ml-3"><i class="fas fa-book"></i> Hướng dẫn học</b>
                                    @endif
                                </div>
                                <div class="_215b05">
                                    @php
                                        switch ($course_time_unit){
                                            case 'day': $time_unit = trans('app.day'); break;
                                            case 'session': $time_unit = trans('app.session'); break;
                                            default : $time_unit = trans('app.hours'); break;
                                        }
                                    @endphp
                                    <div class="_215b05">
                                        <span><i class='uil uil-clock'></i></span>
                                        @lang('app.duration'): {{ $course_time.' '.$time_unit }}
                                    </div>
                                </div>
                                <div class="_215b05">
                                    <b>@lang('app.time'):</b> {{ get_date($item->start_date) }} @if($item->end_date) đến {{ get_date($item->end_date) }} @endif
                                </div>

                                <div class="_215b05">
                                    <b>@lang('app.register_deadline'):</b> {{ get_date($item->register_deadline) }}
                                </div>

                                @if($item->getObject())
                                <div class="_215b05" onclick="openModal({{$item->id}})" style="cursor: pointer;width:100px">
                                    <b>Đối tượng:</b> <i class="uil uil-info-circle" title="{{ $item->getStatus() }}"></i>
                                </div>
                                @endif

                                <ul class="_215b05">
                                    <div id="notify-course" class="mb-3">@lang('app.notify_go_course')</div>
                                    <div class="mb-3">
                                        @php
                                            $promotion_share = \Modules\Promotion\Entities\PromotionShare::query()
                                                ->where('user_id', '=', getUserId())
                                                ->where('user_type', '=', getUserType())
                                                ->where('course_id', '=', $item->id)
                                                ->where('type', '=', 1)
                                                ->first();
                                        @endphp
                                    </div>
                                </ul>

                                <div class="_215b05 tutorial_course">
                                    @php
                                        $status = $item->getStatusRegister();
                                        $text = status_register_text($status);
                                    @endphp
                                    @if($status == 1)
                                        <button data-toggle="modal" data-target="#modal-referer" id="btn_register" class="btn"><i class="far fa-edit"></i> {{ $text }}</button>
                                    @elseif($status == 4)
                                        <button href="javascript:void(0)" class="btn" id="go-course"><i class="far fa-edit"></i> {{ mb_strtoupper($text) }}</button>
                                    @else
                                        <button type="button" class="btn 1"><i class="far fa-edit"></i> {{ $text }}</button>
                                    @endif

                                    @if($item->rating && $register)
                                        @if($item->rating_end_date && $item->rating_end_date < date('Y-m-d H:i:s'))
                                            <div class="mt-2">
                                                Đã kết thúc thời gian đánh giá sau khóa học
                                            </div>
                                        @else
                                        <div class="mt-2">
                                            <a id="review_course" href="{{ isset($rating_course) ? route('module.rating.edit_course', ['type' => 1, 'id' => $item->id]) : route('module.rating.course', ['type' => 1, 'id' => $item->id]) }}" class="btn btn-info"> Đánh giá sau khóa học</a>
                                        </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="_215b05">
                                    @php
                                        $percent = \Modules\Online\Entities\OnlineCourse::percentCompleteCourseByUser
                                        ($item->id, getUserId());
                                    @endphp
                                    <b>Tiến trình đào tạo</b>
                                    <div class="row mx-0">
                                        <div class="progress progress2 {{ $percent == 100 ? 'col-md-9 col-7' : 'col-md-12 col-7' }} p-0 mt-1" style="border-radius: 10px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                                {{ round($percent, 2) }}%
                                            </div>
                                        </div>
                                        @if ($training_process !== null && $item->has_cert == 1 && $item->cert_code !== null)
                                        <div class="col-md-3 col-5" id="certificate">
                                            <a href="{{route('module.backend.user.trainingprocess.certificate', [
                                                        'course_id' => $training_process->course_id,
                                                        'course_type' => $training_process->course_type,
                                                        'user_id' => $training_process->user_id,
                                                    ])}}"
                                                class="btn">
                                                <i class="fas fa-certificate"></i> Chứng chỉ
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
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
                                        <div class="nav nav-pills mb-4 tab_crse justify-content-center" id="nav-tab" role="tablist">
                                            <a class="nav-item nav-link active" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-selected="true">@lang('app.description')</a>
                                            <a class="nav-item nav-link" id="nav-courses-tab" data-toggle="tab" href="#nav-courses" role="tab" aria-selected="false">@lang('app.content')</a>
                                            <a class="nav-item nav-link" id="nav-ask-answer-tab" data-toggle="tab" href="#nav-ask-answer" role="tab" aria-selected="true">Hỏi & đáp</a>
                                            <a class="nav-item nav-link" id="nav-reviews-tab" data-toggle="tab" href="#nav-reviews" role="tab" aria-selected="false">@lang('app.comment')</a>
                                            <a class="nav-item nav-link" id="nav-note-tab" data-toggle="tab" href="#nav-note" role="tab" aria-selected="false">Ghi chép</a>
                                            <a class="nav-item nav-link" id="nav-document-tab" data-toggle="tab" href="#nav-document" role="tab" aria-selected="false">Tài liệu</a>
                                            <a class="nav-item nav-link" id="nav-rating-level-tab" data-toggle="tab" href="#nav-rating-level" role="tab" aria-selected="false"> Đánh giá cấp độ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="_215b17">
                        <div class="container-fluid body_course">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="course_tab_content">
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-about" role="tabpanel">
                                                <div class="_htg451 text-justify">
                                                    {!! $item->content !!}
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="nav-ask-answer" role="tabpanel">
                                                @livewire('online.ask-answer', ['course_id' => $item->id])
                                            </div>

                                            <div class="tab-pane fade" id="nav-courses" role="tabpanel">
                                                <div class="crse_content">
                                                    <ul class="row" id="content_ul">
                                                        <li class="col-9">@lang('app.content')</li>
                                                        <li class="col-3">@lang('backend.completed')</li>
                                                    </ul>
                                                    <div class="crse_content mt-0">
                                                        <div class="row">
                                                            <div id="accordion_table" class="w-100">
                                                                @foreach ($lessons_course as $key => $lesson_course)
                                                                    <div class="card">
                                                                        <div class="card-header" id="heading-{{$lesson_course->id}}">
                                                                            <h5 class="mb-0">
                                                                            <button class="btn btn-link {{$key == 0 ? '' : 'collapsed'}}" data-toggle="collapse" data-target="#collapse-{{$lesson_course->id}}"
                                                                                    aria-expanded={{$key == 0 ? "true" : "false"}}
                                                                                    aria-controls="collapse-{{$lesson_course->id}}">
                                                                                {{$lesson_course->lesson_name}}
                                                                            </button>
                                                                            </h5>
                                                                        </div>

                                                                        <div id="collapse-{{$lesson_course->id}}" class="collapse show" aria-labelledby="heading-{{$lesson_course->id}}" data-parent="#accordion_table">
                                                                            <div class="card-body row">
                                                                                @php
                                                                                    $activities = $item->getActivitiesOfLesson($lesson_course->id);
                                                                                    $activity_scorm = '';
                                                                                @endphp
                                                                                @foreach($activities as $activity)
                                                                                    @php
                                                                                        $bbb = \Modules\VirtualClassroom\Entities\VirtualClassroom::find($activity->subject_id);
                                                                                        $parts = $part($activity->subject_id);
                                                                                        $check_setting_activity = $activity->checkSettingActivity();
                                                                                    @endphp
                                                                                <div class="col-10 pr-0">
                                                                                    @if($status == 4)
                                                                                        @if($activity->activity_id == 1)
                                                                                            @php
                                                                                                $get_course_activity = Modules\Online\Entities\OnlineCourseActivity::findOrFail($activity->id);
                                                                                                $activity_scorm = Modules\Online\Entities\OnlineCourseActivityScorm::findOrFail($get_course_activity->subject_id);
                                                                                            @endphp
                                                                                            <div class="row">
                                                                                                <div class="col-md-11 col-9">
                                                                                                    <a target="_blank"
                                                                                                       @if($check_setting_activity)
                                                                                                    href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id,'lesson' => $lesson_course->id]) }}"
                                                                                                       @endif
                                                                                                    class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all"
                                                                                                    data-turbolinks="false">
                                                                                                    <div class="section-header-left">
                                                                                                        <span class="section-title-wrapper">
                                                                                                            <i class="uil uil-suitcase-alt crse_icon"></i>
                                                                                                            <span class="section-title-text">{{ $activity->name }} </span>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </a>
                                                                                                </div>
                                                                                                <div class="col-md-1 col-2 img_scrom" onclick="open_history_scrom({{$activity_scorm->id}})">
                                                                                                    <img src="{{asset('images/scrom.jpg')}}" width="40px" alt="">
                                                                                                </div>
                                                                                            </div>
                                                                                            @include('online::modal.history_scrom')
                                                                                        @endif

                                                                                        @if($activity->activity_id == 2)
                                                                                            @if(is_null($parts))
                                                                                                <a href="javascript:void(0)" class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
                                                                                                    <div class="section-header-left">
                                                                                                        <span class="section-title-wrapper">
                                                                                                            <i class="uil uil-file-check crse_icon"></i>
                                                                                                            <span class="section-title-text">
                                                                                                                {{ $activity->name .' ('. data_locale('Kỳ thi đã kết thúc hoặc Bạn chưa đăng kí kỳ thi', 'The exam has ended or You have not registered for it')  .')' }}
                                                                                                            </span>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </a>
                                                                                            @elseif(isset($parts) && $parts->start_date > date('Y-m-d H:i:s'))
                                                                                                <a href="javascript:void(0)" class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
                                                                                                    <div class="section-header-left">
                                                                                                        <span class="section-title-wrapper">
                                                                                                            <i class="uil uil-file-check crse_icon"></i>
                                                                                                            <span class="section-title-text">
                                                                                                                {{ $activity->name .' ('. data_locale('Kỳ thi chưa tới giờ', 'Quiz is not yet time') .')' }}
                                                                                                            </span>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </a>
                                                                                            @else
                                                                                                <a @if($check_setting_activity)
                                                                                                    href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}"
                                                                                                   @endif
                                                                                                class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" data-turbolinks="false">
                                                                                                    <div class="section-header-left">
                                                                                                        <span class="section-title-wrapper">
                                                                                                            <i class="uil uil-file-check crse_icon"></i>
                                                                                                            <span class="section-title-text">{{ $activity->name }}</span>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </a>
                                                                                            @endif
                                                                                        @endif

                                                                                        @if($activity->activity_id == 3)
                                                                                            <a @if($check_setting_activity)
                                                                                                href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}"
                                                                                               @endif
                                                                                                class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" data-turbolinks="false">
                                                                                                <div class="section-header-left">
                                                                                                    <span class="section-title-wrapper">
                                                                                                        <i class="uil uil-file crse_icon"></i>
                                                                                                        <span class="section-title-text">{{ $activity->name }}</span>
                                                                                                    </span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @endif

                                                                                        @if($activity->activity_id == 4)
                                                                                            <a @if($check_setting_activity)
                                                                                                href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}"
                                                                                               @endif
                                                                                                class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" data-turbolinks="false">
                                                                                                <div class="section-header-left">
                                                                                                    <span class="section-title-wrapper">
                                                                                                        <i class="uil uil-link crse_icon"></i>
                                                                                                        <span class="section-title-text">{{ $activity->name }}</span>
                                                                                                    </span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @endif

                                                                                        @if($activity->activity_id == 5)
                                                                                            <a @if($check_setting_activity)
                                                                                                href="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}"
                                                                                               @endif
                                                                                                class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" data-turbolinks="false">
                                                                                                <div class="section-header-left">
                                                                                                    <span class="section-title-wrapper">
                                                                                                        <i class="uil uil-video crse_icon"></i>
                                                                                                        <span class="section-title-text">{{ $activity->name }}</span>
                                                                                                    </span>
                                                                                                </div>
                                                                                            </a>
                                                                                        @endif

                                                                                        @if($activity->activity_id == 6 && isset($bbb))
                                                                                            @if($bbb->start_date > date('Y-m-d H:i:s'))
                                                                                                <a href="javascript:void(0)" class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
                                                                                                    <div class="section-header-left">
                                                                                                        <span class="section-title-wrapper">
                                                                                                            <i class="uil uil-skip-forward crse_icon"></i>
                                                                                                            <span class="section-title-text">
                                                                                                                {{ $activity->name .' ('. data_locale('Lớp học chưa tới giờ', 'Class is not yet time') .')' }}
                                                                                                            </span>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </a>
                                                                                            @elseif($bbb->end_date < date('Y-m-d H:i:s'))
                                                                                                <a href="javascript:void(0)" class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
                                                                                                    <div class="section-header-left">
                                                                                                        <span class="section-title-wrapper">
                                                                                                            <i class="uil uil-skip-forward crse_icon"></i>
                                                                                                            <span class="section-title-text text-black-50">
                                                                                                                {{ $activity->name .' ('. data_locale('Lớp học đã kết thúc', 'Class has ended') .')' }}
                                                                                                            </span>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </a>
                                                                                            @else
                                                                                                <a href="javascript:void(0)" class="@if($check_setting_activity) go-bbb @endif accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all" data-turbolinks="false"
                                                                                                   @if($check_setting_activity)
                                                                                                   data-url="{{ route('module.online.goactivity', ['id' => $item->id, 'aid' => $activity->id, 'lesson' => $lesson_course->id]) }}"
                                                                                                    @endif
                                                                                                >
                                                                                                    <div class="section-header-left">
                                                                                                        <span class="section-title-wrapper">
                                                                                                            <i class="uil uil-skip-forward crse_icon"></i>
                                                                                                            <span class="section-title-text">{{ $activity->name }}</span>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </a>
                                                                                            @endif
                                                                                        @endif
                                                                                    @else
                                                                                        <div class="accordion-header ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all">
                                                                                            <div class="section-header-left">
                                                                                                <span class="section-title-wrapper">
                                                                                                    <i class="uil uil-document crse_icon"></i>
                                                                                                    <span class="section-title-text">{{ $activity->name }}</span>
                                                                                                </span>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-2">
                                                                                    <div class="opts_account_course">
                                                                                        @if($activity->isComplete(getUserId(), getUserType()))
                                                                                            <center>
                                                                                                <img src="{{ asset('themes/mobile/img/check.png') }}" class="h-auto ml-0 mt-2">
                                                                                            </center>
                                                                                        @else
                                                                                            <center>
                                                                                            <img src="{{ asset('themes/mobile/img/circle.png') }}" class="h-auto ml-0 mt-2">
                                                                                            </center>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="nav-reviews" role="tabpanel">
                                                @livewire('online.comment', ['course_id' => $item->id,'avg_star' => $item->avgRatingStar()])
                                            </div>

                                            <div class="tab-pane fade" id="nav-note" role="tabpanel">
                                                @livewire('online.note', ['course_id' => $item->id])
                                            </div>
                                            <div class="tab-pane fade" id="nav-document" role="tabpanel">
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
                                            <div class="tab-pane fade" id="nav-rating-level" role="tabpanel">
                                                <table class="tDefault table table-hover bootstrap-table" id="table-rating-level">
                                                    <thead>
                                                    <tr>
                                                        <th data-field="rating_name" data-formatter="rating_name_formatter">Tên đánh giá</th>
                                                        <th data-field="course_name">Tên khoá học</th>
                                                        <th data-field="course_time">Thời gian khoá học</th>
                                                        <th data-field="rating_time">Thời gian đánh giá</th>
                                                        <th data-field="rating_status" data-align="center" >Tình trạng</th>
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

    @include('online::modal.referer')

    {{-- MOdal SHOW ĐỐI TƯỢNG --}}
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Đối tượng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="tDefault table table-hover bootstrap-table" id="table-object">
                            <thead>
                                <tr>
                                    <th data-align="center" data-width="3%" data-formatter="stt_formatter">STT</th>
                                    <th data-field="title_name">{{trans('backend.title')}}</th>
                                    <th data-field="unit_name">{{trans('backend.unit')}}</th>
                                    <th data-align="center" data-field="type" data-width="10%" data-formatter="type_formatter">{{trans('backend.type_object')}}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        window.Rating = {
            route: '{{ route('module.online.rating',$item->id) }}',
        };
        var rating = $('.rating');
        ratingStars(rating);

        $('#notify-course').prop('hidden', true);
        var status = '<?php echo $status ?>';
        if (status == 4) {
            $('a[data-toggle="tab"]').removeClass('active');
            $('a[data-toggle="tab"]').attr('aria-selected',false);

            $('a[href="#nav-courses"]').attr('aria-selected',true);
            $('a[href="#nav-courses"]').addClass('active');

            $('#nav-tabContent .tab-pane').removeClass('show active');
            $('#nav-courses').addClass('show active');

            $('#go-course').prop('hidden', true);
            $('#notify-course').prop('hidden', false);
        }

        $('#nav-courses').on('click', '.go-bbb', function () {
            var url = $(this).data('url');

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Thông báo',
                text: "Anh/Chị có chắc chắn muốn tham gia khóa học này?",
                showCancelButton: true,
                confirmButtonText: 'Tham gia',
                cancelButtonText: 'Không tham gia',
            }).then((result) => {
                if (result.value) {
                    window.open(url, "_blank");
                }
            })
        });

        $('#tutorial_course').on('click',function() {
            $('#modal-tutorial').modal();
        });

        var percent = '<?php echo $percent ?>';
        $('#certificate').css("display", "none");
        if (percent == 100) {
            $('#certificate').css("display", "inline-block");
        }

        // Share link khóa học
        $('#share-course').on('click', function () {
            var share_key = Math.random().toString(36).substring(3);
            $.ajax({
                type: "POST",
                url: "{{ route('module.online.detail.share_course', ['id' => $item->id, 'type' => 1]) }}",
                data:{
                    share_key: share_key,
                },
                success: function (data) {
                    console.log(data.key);
                    $('#modal-body-share').html(`<b>Link share:</b>
                                                <span id="copy_link_share_{{$item->id}}"">
                                                    {{ route('module.online.detail', ['id' => $item->id]).'?share_key='}}`+ data.key+
                                                `</span>`);
                    $('#modal-share').modal();
                }
            });
        });
        function copyShare(id) {
            var copyText = document.getElementById("copy_link_share_"+id);
            if(window.getSelection) {
                // other browsers
                var selection = window.getSelection();
                var range = document.createRange();
                range.selectNodeContents(copyText);
                selection.removeAllRanges();
                selection.addRange(range);
                document.execCommand("Copy");
                // alert("Sao chép link share");
            }
        }

        function openModal(id) {
            $('#modal').modal();
        }
        function type_formatter(value, row, index) {
            return value == 1 ? 'Bắt buộc' : '{{ trans("backend.register") }}';
        }

        function stt_formatter(value, row, index) {
            return (index + 1);
        }
        var table_object = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.get_object', ['id' => $item->id]) }}',
            detete_button: '#delete-object',
            table: '#table-object'
        });
        function closeModal(id) {
            $('#referer_'+id).val('');
            var form =  $('#frm-course-'+id);
            form.submit();
        }

        // Lịch sử scrom
        function index_formatter_scrom(value, row, index) {
            return (index+1)
        }

        function open_history_scrom(id) {
            var url = "{{ route('module.online.attempts', ':id') }}";
            url = url.replace(':id',id);
            var table_scrom = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: url,
                table: '#table-scrom-'+id
            });
            $('#modal-scrom-'+id).modal();
        }

        function rating_name_formatter(value, row, index) {
            if(row.rating_level_url){
                return '<a href="'+ row.rating_level_url +'">'+ row.rating_name +'</a>';
            }
            return row.rating_name;
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.online.detail.rating_level.getdata', ['id' => $item->id]) }}',
            table: '#table-rating-level',
        });
    </script>
@endsection
