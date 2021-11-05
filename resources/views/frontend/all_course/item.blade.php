@php
    $url = $item->course_type == 1 ? route('module.online.detail', ['id' => $item->course_id]) : route('module.offline.detail', ['id' => $item->course_id]);

    $url2 = $item->course_type == 1 ? route('module.online.detail_online', ['id' => $item->course_id]) : route('module.offline.detail', ['id' => $item->course_id]);

    $item->getStatus($item->course_type);
    $get_promotion = \Modules\Promotion\Entities\PromotionCourseSetting::where('course_id',$item->course_id)->where('type',$type)->first();
    $get_bookmarked = \App\CourseBookmark::where('course_id',$item->course_id)->where('type',$type)->where('user_id',\Auth::id())->first();
@endphp
<div class="fcrse_1 mb-20">
    <a href="{{ $url2 }}" class="fcrse_img">
        <img class="picture_course" src="{{ image_file($item->image) }}" alt="">
        <div class="course-overlay">
            @if ( !empty($get_promotion) )
                @php
                    if ($get_promotion->method == 1)
                        $point = $get_promotion->point;
                    else{
                        $setting = $get_promotion->methodSetting->sortByDesc('point');
                        $point = $setting->count() > 0 ? $setting->first()->point : 0;
                    }
                @endphp
                <div class="badge_seller">
                    {{ $point }} 
                    <img class="point ml-1" style="width: 20px;height: 20px" src="{{ asset('styles/images/level/point.png') }}" alt="">
                </div>
            @endif
            <div class="crse_reviews">
                <i class='uil uil-star'></i>{{ $item->avgRatingStar($type) }}
            </div>
        </div>
    </a>
    <div class="fcrse_content">
        <div class="eps_dots more_dropdown check_course">
            <a href="javascript:void(0)"><i class='uil uil-ellipsis-v'></i></a>
            <div class="dropdown-content">
                <span>
                    <i class='uil uil-heart-alt'></i>
                    @if (!empty($get_bookmarked))
                        <a href="{{ route('frontend.home.remove_course_bookmark',['course_id'=>$item->course_id,'course_type'=>$type, 'my_course'=> 0]) }}" class="item-bookmark">
                            @lang('app.unbookmark')
                        </a>
                    @else
                        <a href="{{ route('frontend.home.save_course_bookmark',['course_id'=>$item->course_id,'course_type'=>$type, 'my_course' => 0]) }}" class="item-bookmark">
                            @lang('app.bookmark')
                        </a>
                    @endif
                </span>
                @php
                    $check_promotion_course_setting = \Modules\Promotion\Entities\PromotionCourseSetting::where('course_id',$item->course_id)->exists();
                @endphp
                @if ($check_promotion_course_setting)
                    <span onclick="openModalBonus({{$item->course_id}},{{$type}})">
                        <img class="image_bonus_courses" src="{{asset("images/level/point.png")}}" alt="" width="29px" height="15px">
                        Điểm thưởng
                    </span>
                @endif
                <span href="javascript:void(0)" style="cursor: pointer" class="ml-1" onclick="shareCourse({{$item->course_id}},{{$type}})">
                    <i class="fas fa-link mr-2"></i> 
                    Share
                </span>
            </div>
        </div>
        <div class="vdtodt">
            <span class="vdt14"><i class="uil uil-windsock"></i>{{ $item->register($item->course_type)->count() }} @lang('app.joined')</span>
            <span class="vdt14"><i class='uil uil-heart {{ !empty($get_bookmarked) ? 'check-heart' : ''}}'></i> {{ !empty($get_bookmarked) ? __('app.bookmarked') : __('app.bookmark') }}</span>
        </div>
        <div class="course_names">
            <a href="{{ $url2 }}" class="crse14s course_name">{{ $item->name }}</a>
            <span class="hidden_name">{{ $item->name }}</span>
        </div>
        <div class="vdtodt">
            <span class="vdt14"><b>Mã khóa học:</b> {{$item->code}}</span>
        </div>

        <div class="vdtodt" onclick="openModalDescription({{$item->course_id}},{{$type}})" style="cursor: pointer">
            <span class="vdt14"><b>Mô tả:</b> Chi tiết</span>
        </div>

        <div class="vdtodt">
            <span class="vdt14"><b>@lang('app.time'):</b> {{ get_date($item->start_date) }} @if($item->end_date) @lang('app.to') {{ get_date($item->end_date) }} @endif</span>
        </div>
        <div class="vdtodt">
            <span class="vdt14"><b>@lang('app.register_deadline'):</b> {{ get_date($item->register_deadline) }}</span>
        </div>
        <div class="vdtodt">
            <span class="vdt14"><b>Điểm đạt:</b> {{$item->min_grades}}</span>
        </div>
        <div class="vdtodt">
            <span class="vdt14"><b>Hình thức:</b> {{ $type == 1 ? 'Online' : 'Tập trung' }}</span>
        </div>
        <div class="vdtodt" onclick="openModalObject({{$item->course_id}},{{$type}})" style="cursor: pointer">
            <p class="cr1fot import-plan"><b>Đối tượng:</b> <i title="{{ $item->getStatus($item->course_type) }}">Chi tiết</i></p>
        </div>
        @php
            $check_course_complete = \App\Models\CourseComplete::where('course_id',$item->course_id)->where('course_type',$item->course_type)->where('user_id', \Auth::id())->first();
            $status = $item->getStatusRegister( $item->course_type );
            $text = status_register_text($status);
            if ($type == 1) {
                $percent = \Modules\Online\Entities\OnlineCourse::percentCompleteCourseByUser($item->course_id, \Auth::id());
            } else {
                $percent = 0;
            }
        @endphp
        <div class="auth1lnkprce">
            <div class="row">
                <div class="col-4 chart">
                    <input type="hidden" name="text" class="canvas_percent" value="{{ $item->course_id }},{{ $type }},{{ $percent }},{{ $status }}">
                    @if ($status == 4 && $type == 1)
                        <canvas id="chartProgress_{{$item->course_id}}_{{ $type }}" width="80px" height="80px"></canvas>
                    @endif
                </div>
                <div class="prce142 col-8 button_course">
                    @if($status == 1 && empty($check_course_complete))
                        <div class="mt-2 item item-btn">
                            <button id="btn_register_{{$item->course_id}}_{{ $type }}" class="btn btn_adcart" onclick="submitRegister({{$item->course_id}},{{$type}})">{{ $text }}</button>
                        </div>
                    @elseif($status == 4 && empty($check_course_complete))
                        <div class="mt-2">
                            <button onclick="window.location.href='{{ $url2 }}'" class="btn btn_adcart">Vào học</button>
                        </div>
                    @elseif ( !empty($check_course_complete) )
                        <div class="mt-2">
                            <button onclick="window.location.href='{{ $url2 }}'" class="btn btn_adcart">Hoàn thành</button>
                        </div>
                    @else
                        <div class="mt-2">
                            <button onclick="endCourse({{ $item->course_id }},{{ $type }},{{ $status }})" type="button" class="btn btn_adcart">{{ $text }}</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>