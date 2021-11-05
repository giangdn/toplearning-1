<div class="tab-pane fade active show" id="nav-courses" role="tabpanel">
    <div class="crse_content mt-2">
        <h3>
            @if ($type == 1)
                {{ trans('lacourse.my_online_course') }}Khóa học Trực tuyến của tôi ({{ $total }})
            @elseif ($type ==2)
                {{ trans('lacourse.my_offline_course') }}Khóa học Tập trung của tôi ({{ $total }})
            @else
                {{ trans('lacourse.my_course') }}Khóa học của tôi ({{ $total }})
            @endif
        </h3>
        <span class="search_course">
            <form method="get" class="form-inline form-search mb-2" id="form-search">
                <input type="text" name="q" class="form-control input-search" autocomplete="off" placeholder="{{ trans('app.search') .' '. trans('app.course') }}" value="{{ request()->get('q') }}">
                @if ($type == 0)
                    <select name="type_course" class="form-control select-course-type" id="">
                        <option value="" selected disabled>Chọn khóa học</option>
                        <option value="1">{{ trans('lacourse.online_course') }}Khóa học Trực tuyến</option>
                        <option value="2">{{ trans('lacourse.offline_course') }}Khóa học Tập trung</option>
                    </select>
                @endif

                <input name="start_date" type="text" class="datepicker form-control search_start_date" placeholder="{{trans('backend.start_date')}}" autocomplete="off">
                <input name="end_date" type="text" class="datepicker form-control search_end_date" placeholder="{{trans('backend.end_date')}}" autocomplete="off">
                <button class="btn btn-info btn-search" type="submit"><i class="fa fa-search"></i></button>
            </form>
        </span>
        <div class="_14d25 mt-0">
            <div class="row">
                @if (!empty($items))
                    @foreach($items as $item)
                        @php
                            switch ($item->course_time_unit){
                                case 'day': $time_unit = trans('app.day'); break;
                                case 'session': $time_unit = trans('app.session'); break;
                                default : $time_unit = trans('app.hours'); break;
                            }
                            if ($item->course_type == 1){
                                $percent = \Modules\Online\Entities\OnlineCourse::percentCompleteCourseByUser($item->course_id, \Auth::id());
                                $get_course = Modules\Online\Entities\OnlineCourse::where('id',$item->course_id)->first();
                            }else{
                                $percent = \Modules\Offline\Entities\OfflineCourse::percent($item->course_id, \Auth::id());
                                $get_course = Modules\Offline\Entities\OfflineCourse::where('id',$item->course_id)->first();
                            }
                            $url = $item->course_type == 1 ? route('module.online.detail_online', ['id' => $item->course_id]) : route('module.offline.detail', ['id' => $item->course_id]);
                        @endphp

                            <div class="col-lg-3 col-md-4 px-2">
                                <div class="fcrse_1">
                                    <a href="{{ $url }}" class="fcrse_img">
                                        <img src="{{ image_file($item->image) }}" alt="" class="picture_course">
                                        <div class="course-overlay">
                                            {{--<div class="badge_seller">Bestseller</div>--}}
                                            <div class="crse_reviews">
                                                <i class="uil uil-star"></i>{{ $item->avg_rating_star }}
                                            </div>
                                            <span class="play_btn1"><i class="uil uil-play"></i></span>
                                            <div class="crse_timer">
                                                {{ $item->course_time .' '. $time_unit}}
                                            </div>
                                        </div>
                                    </a>
                                    <div class="fcrse_content">
                                        <div class="vdtodt">
                                            @if (isset($get_course))
                                            <span class="vdt14"><i class="uil uil-windsock"></i>{{ $get_course->register->count() }} @lang('app.joined')</span>
                                            <span class="vdt14"><i class='uil uil-heart {{ $get_course->bookmarked ? 'check-heart' : ''}}'></i> {{ $get_course->bookmarked ? __('app.bookmarked') : __('app.bookmark') }}</span>
                                            @endif
                                            @if($user_type == 1)
                                            <div class="eps_dots more_dropdown check_course">
                                                <a href="javascript:void(0)"><i class='uil uil-ellipsis-v'></i></a>
                                                <div class="dropdown-content">
                                                    <span>
                                                        <i class='uil uil-heart-alt'></i>
                                                        @if (isset($get_course) && $get_course->bookmarked)
                                                            <a href="{{ route('frontend.home.remove_course_bookmark',['course_id'=>$item->course_id,'course_type'=>$item->course_type, 'my_course'=> 1 ]) }}" class="item-bookmark">
                                                            @lang('app.unbookmark')
                                                        </a>
                                                        @else
                                                        <a href="{{ route('frontend.home.save_course_bookmark',['course_id'=>$item->course_id,'course_type'=>$item->course_type, 'my_course' => 1]) }}" class="item-bookmark">
                                                            @lang('app.bookmark')
                                                        </a>
                                                        @endif
                                                    </span>
                                                    @php
                                                        $check_promotion_course_setting = \Modules\Promotion\Entities\PromotionCourseSetting::where('course_id',$item->course_id)->exists();
                                                    @endphp
                                                    @if ($check_promotion_course_setting)
                                                        <span onclick="openModalBonus({{$item->course_id}})">
                                                            <img class="image_bonus_courses" src="{{asset("images/level/point.png")}}" alt="" width="29px" height="15px">
                                                            Điểm thưởng
                                                        </span>
                                                    @endif
                                                    <span href="javascript:void(0)" style="cursor: pointer" class="ml-1" onclick="shareCourse({{$item->course_id}})">
                                                        <i class="fas fa-link mr-2"></i>
                                                        Share
                                                    </span>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="vdtodt">
                                            <span class="vdt14">
                                                <b>
                                                    @if ($item->course_type == 1)
                                                        @lang('app.onl_course')
                                                    @else
                                                        @lang('app.off_course')
                                                    @endif
                                                </b>
                                            </span>
                                            <span class="vdt14">{{ $item->views }} {{ trans('app.view') }}</span>
                                        </div>
                                        <div class="course_names">
                                            <a href="{{ $url }}" class="crse14s course_name">{{ $item->name }}</a>
                                            <span class="hidden_name">{{ $item->name }}</span>
                                        </div>
                                        <div class="vdtodt">
                                            <span class="vdt14"><b>{{ trans('lacourse.course_code') }}:</b> {{$item->code}}</span>
                                        </div>
                                        <div class="vdtodt">
                                            <span class="vdt14"><b>@lang('app.time'):</b> {{ $item->start_date }} @if($item->end_date) @lang('app.to') {{ $item->end_date }} @endif</span>
                                        </div>
                                        <div class="vdtodt">
                                            <span class="vdt14"><b>@lang('app.register_deadline') : </b>{{ get_date($item->register_deadline) }}</span>
                                        </div>
                                        <div class="vdtodt">
                                            <span class="vdt14"><b>Điểm đạt:</b> {{ isset($get_course) ? $get_course->min_grades : '' }}</span>
                                        </div>
                                        <div class="vdtodt" onclick="openModalObject({{$item->course_id}},{{$item->course_type}})" style="cursor: pointer">
                                            <p class="cr1fot import-plan"><b>Đối tượng:</b> Chi tiết</i></p>
                                        </div>
                                        <div class="vdtodt float-right">
                                            <span class="vdt14">
                                                <b>
                                                    @if ($item->course_type == 1)
                                                        Online
                                                    @else
                                                        In house
                                                    @endif
                                                </b>
                                            </span>
                                        </div>
                                        @php
                                            $check_course_complete = \App\Models\CourseComplete::where('course_id',$item->course_id)->where('course_type',$item->course_type)->where('user_id', \Auth::id())->first();
                                            $status = isset($get_course) ? $get_course->getStatusRegister() : '';
                                            $text = status_register_text($status);
                                        @endphp
                                        <div class="auth1lnkprce">
                                            <div class="row">
                                                <div class="col-5 chart">
                                                    <canvas id="chartProgress_{{$item->course_id}}" width="80px" height="80px"></canvas>
                                                </div>
                                                <div class="prce142 col-7 button_course">
                                                    @if($status == 4 && empty($check_course_complete))
                                                        <div class="mt-2">
                                                            <button onclick="window.location.href='{{ $url }}'" class="btn btn_adcart">Vào học</button>
                                                        </div>
                                                    @elseif ( !empty($check_course_complete) )
                                                        <div class="mt-2">
                                                            <button onclick="window.location.href='{{ $url }}'" class="btn btn_adcart">Hoàn thành</button>
                                                        </div>
                                                    @else
                                                        <div class="mt-2">
                                                            <button onclick="endCourse({{ $item->course_id }},{{ $item->course_type }},{{ $status }})" type="button" class="btn btn_adcart">{{ $text }}</button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        {{-- MOdal SHOW ĐỐI TƯỢNG --}}
                        <div class="modal fade" id="modal_{{$item->course_id}}_{{ $item->course_type }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel_{{$item->course_id}}_{{ $item->course_type }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="" method="post" class="form-ajax">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel_{{$item->course_id}}_{{ $item->course_type }}">Đối tượng</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body model_body_object_my_course pt-0 mt-2">
                                            @php
                                                $titles_join = [];
                                                $titles_recomment = [];
                                                $get_titles_join = json_decode($get_course->title_join_id);
                                                $get_titles_recomment = json_decode($get_course->title_recommend_id);
                                                if(!empty($get_titles_join) && !in_array(0,$get_titles_join)) {
                                                    foreach ($get_titles_join as $key => $get_title_join) {
                                                        $get_title = \App\Models\Categories\Titles::find($get_title_join);
                                                        $titles_join[] = $get_title->name;
                                                    }
                                                } elseif (!empty($get_titles_join) && in_array(0,$get_titles_join)) {
                                                    $get_title = \App\Models\Categories\Titles::where('status',1)->get();
                                                    foreach ($get_title as $key => $value) {
                                                        $titles_join[] = $value->name;
                                                    }
                                                }

                                                if(!empty($get_titles_recomment) && !in_array(0,$get_titles_recomment)) {
                                                    foreach ($get_titles_recomment as $key => $get_title_recomment) {
                                                        $title_recomment = \App\Models\Categories\Titles::find($get_title_recomment);
                                                        $titles_recomment[] = $title_recomment->name;
                                                    }
                                                } elseif (!empty($get_titles_recomment) && in_array(0,$get_titles_recomment)) {
                                                    $title_recomment = \App\Models\Categories\Titles::where('status',1)->get();
                                                    foreach ($title_recomment as $key => $value) {
                                                        $titles_recomment[] = $value->name;
                                                    }
                                                }
                                            @endphp
                                            <table class="table table-bordered table-striped" id="table_object_{{$item->course_id}}_{{ $item->course_type }}">
                                                <thead>
                                                    <tr>
                                                        <th data-field="title_name">{{trans('backend.title')}}</th>
                                                        <th data-align="center" data-field="type" data-width="10%" data-formatter="type_formatter">{{trans('backend.type_object')}}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($titles_join))
                                                        @foreach ($titles_join as $title_join)
                                                            <tr>
                                                                <td>{{ $title_join }}</td>
                                                                <td>Bắt buộc</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    @if (!empty($titles_recomment))
                                                        @foreach ($titles_recomment as $title_recomment)
                                                            <tr>
                                                                <td>{{ $title_recomment }}</td>
                                                                <td>Khuyến khích</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- MODAL LINK SHARE --}}
                        <div class="modal fade modal-add-activity" id="modal-share-{{$item->course_id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Share link khóa học</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body" id="modal-body-share-{{$item->course_id}}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn" onclick="copyShare({{$item->course_id}})">Copy</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL ĐIỂM THƯỞNG --}}
                        <div class="modal fade modal-add-activity" id="modal-bonus-{{$item->course_id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">
                                            <img class="image_bonus_courses" src="{{asset("images/level/point.png")}}" alt="" width="20px" height="15px">
                                            Điểm thưởng
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $arr_code = [
                                                'assessment_after_course' => 'Đánh giá sau khóa học',
                                                'evaluate_training_effectiveness' => 'Đánh giá hiệu quả đào tạo',
                                                'rating_star' => 'Đánh giá sao',
                                                'share_course' => 'Share khóa học'
                                            ];
                                            $complete = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($item->course_id, 1, 'complete');
                                            $landmarks = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($item->course_id, 1, 'landmarks');
                                            $rating_star = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($item->course_id, 1, 'rating_star');
                                        @endphp
                                        <div class="promotion-enable">
                                            <div class="form-group row">
                                                <div class="col-sm-3 control-label">
                                                    <h6>{{ trans('backend.scoring_method') }}:</h6>
                                                </div>
                                                <div class="col-md-9">
                                                    @if ($complete)
                                                        <div class="form-check form-check-inline">
                                                            <div class="custom-control custom-radio promotion_0_radio">
                                                                <input type="radio" class="custom-control-input point-type" id="promotion_0_{{$item->course_id}}" onclick="checkBoxBonus({{$item->course_id}})" name="method" value="0">
                                                                <label class="custom-control-label" for="promotion_0_{{$item->course_id}}">{{ trans('backend.complete_course') }}</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($landmarks)
                                                        <div class="form-check form-check-inline promotion_1_radio">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input point-type" id="promotion_1_{{$item->course_id}}" onclick="checkBoxBonus({{$item->course_id}})" name="method" value="1">
                                                                <label class="custom-control-label" for="promotion_1_{{$item->course_id}}">{{ trans('backend.landmarks') }}</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($rating_star)
                                                        <div class="form-check form-check-inline promotion_2_radio">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" class="custom-control-input point-type" id="promotion_2_{{$item->course_id}}" onclick="checkBoxBonus({{$item->course_id}})" name="method" value="2">
                                                                <label class="custom-control-label" for="promotion_2_{{$item->course_id}}">{{ trans('backend.other') }}</label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($complete)
                                                <div class="promotion_0_group_{{$item->course_id}}">
                                                    <div class="form-group row">
                                                        <div class="col-sm-3 control-label"></div>
                                                        <div class="col-md-9">
                                                            <input name="start_date" readonly type="text" class="form-control w-25 d-inline-block datepicker" placeholder="Bắt đầu" autocomplete="off" value="{{ $complete && $complete->start_date ? get_date($complete->start_date) : '' }}">
                                                            <input name="end_date" readonly type="text" class="form-control w-25 d-inline-block datepicker" placeholder="Kết thúc" autocomplete="off" value="{{ $complete && $complete->end_date ? get_date($complete->end_date) : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-3 control-label"></div>
                                                        <div class="col-md-4">
                                                            <input name="point_complete" readonly type="text" class="form-control" placeholder="{{ trans('backend.bonus_points') }}" autocomplete="off" value="{{ $complete ? $complete->point : '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($landmarks)
                                                <div class="promotion_1_group_{{$item->course_id}}">
                                                    <div class="row promotion-table">
                                                        <div class="col-md-12">
                                                            <table class="tDefault table table-hover bootstrap-table" id="table_setting_{{$item->course_id}}">
                                                                <thead>
                                                                <tr>
                                                                    <th data-align="center" data-width="3%" data-formatter="stt_formatter_bonus">STT</th>
                                                                    <th data-field="score" data-align="center">{{ trans('backend.landmarks') }}</th>
                                                                    <th data-field="point" data-align="center">{{ trans('backend.bonus_points') }}</th>
                                                                </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="promotion_2_group_{{$item->course_id}}">
                                                @foreach($arr_code as $key => $code)
                                                    @php
                                                        $other = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($item->course_id, 1, $key);
                                                    @endphp
                                                    @if ($other)
                                                        <div class="form-group row">
                                                            <div class="col-sm-3 control-label"></div>
                                                            <div class="col-md-4">
                                                                {{ $code }}
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input name="point[]" readonly type="text" class="form-control" placeholder="{{ trans('backend.bonus_points') }}" autocomplete="off" value="{{ $other ? $other->point : '' }}">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            var percent = '<?php echo $percent ?>';
                            var id = '<?php echo $item->course_id ?>';
                            if (percent > 0 ) {
                                var myChartCircle = new Chart('chartProgress_'+id, {
                                    type: 'doughnut',
                                    data: {
                                        datasets: [
                                            {
                                                label: 'Hoàn thành',
                                                percent: percent,
                                                backgroundColor: ['#5283ff']
                                            },
                                        ]
                                    },
                                    plugins: [{
                                        beforeInit: (chart) => {
                                                const dataset = chart.data.datasets[0];
                                                chart.data.labels = [dataset.label];
                                                dataset.data = [dataset.percent, 100 - dataset.percent];
                                            }
                                        },
                                        {
                                        beforeDraw: (chart) => {
                                                var width = chart.chart.width,
                                                height = chart.chart.height,
                                                ctx = chart.chart.ctx;
                                                ctx.restore();
                                                var fontSize = (height / 100).toFixed(2);
                                                ctx.font = fontSize + "em sans-serif";
                                                ctx.fillStyle = "#9b9b9b";
                                                ctx.textBaseline = "middle";
                                                var text = parseFloat(chart.data.datasets[0].percent).toFixed(1) + "%",
                                                textX = Math.round((width - ctx.measureText(text).width) / 2),
                                                textY = height / 2;
                                                ctx.fillText(text, textX, textY);
                                                ctx.save();
                                            }
                                        }
                                    ],
                                    options: {
                                        responsive: false,
                                        legend: {
                                            display: false
                                        },
                                        hover: {mode: null},
                                        tooltips: {enabled: false},
                                    }
                                });
                            }
                            var complete = '<?php echo $complete  ?>';
                            var landmarks = '<?php echo $landmarks  ?>';
                            var other = '<?php echo $other ?>';
                            if (landmarks !== '' && other == '' && complete == '') {
                                $(".promotion_0_group_{{$item->course_id}}").hide();
                                $(".promotion_1_group_{{$item->course_id}}").show();
                                $(".promotion_2_group_{{$item->course_id}}").hide();
                            } else if (landmarks == '' && other !== '' && complete == '') {
                                $(".promotion_0_group_{{$item->course_id}}").hide();
                                $(".promotion_1_group_{{$item->course_id}}").hide();
                                $(".promotion_2_group_{{$item->course_id}}").show();
                            } else {
                                $(".promotion_0_group_{{$item->course_id}}").show();
                                $(".promotion_1_group_{{$item->course_id}}").hide();
                                $(".promotion_2_group_{{$item->course_id}}").hide();
                            }
                        </script>
                    @endforeach
                @endif
            </div>
            {{ $items->links() }}
        </div>
    </div>
</div>

{{-- HÉT HẠN ĐĂNG KÝ --}}
<div class="modal fade modal-add-activity" id="modal-end-course">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title modal_title_notification">
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body modal_body_notification">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    // MỞ MODAL ĐỐI TƯỢNG
    function openModalObject(id,type) {
        $('#modal_'+id+'_'+type).modal();
    }

    // Điểm thưởng
    function stt_formatter_bonus(value, row, index) {
        return (index + 1);
    }

    function openModalBonus(id) {
        $('#modal-bonus-'+id).modal();
    }

    function checkBoxBonus(id) {
        if ($("#promotion_0_"+id).is(":checked")) {
            $(".promotion_0_group_"+id).show();
            $(".promotion_1_group_"+id).hide();
            $(".promotion_2_group_"+id).hide();
        }

        if ($("#promotion_1_"+id).is(":checked")) {
            $(".promotion_0_group_"+id).hide();
            $(".promotion_1_group_"+id).show();
            $(".promotion_2_group_"+id).hide();
            var url = "{{ route('module.promotion.get_setting', ['courseId' => ':id', 'course_type' => 1, 'code' => 'landmarks']) }}";
            url = url.replace(':id',id);
            var table_bonus = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: url,
                table: '#table_setting_'+id
            });
        }

        if ($("#promotion_2_"+id).is(":checked")) {
            $(".promotion_0_group_"+id).hide();
            $(".promotion_1_group_"+id).hide();
            $(".promotion_2_group_"+id).show();
        }
    }

    // Share khóa học
    function shareCourse(id) {
        var share_key = Math.random().toString(36).substring(3);
        var url = "{{ route('module.online.detail.share_course', ['id' => ':id', 'type' => 1]) }}";
        url = url.replace(':id',id);
        $.ajax({
            type: "POST",
            url: url,
            data:{
                share_key: share_key,
            },
            success: function (data) {
                var url_link = "{{ route('module.online.detail', ['id' => ':id']).'?share_key=' }}";
                url_link = url_link.replace(':id',id);
                $('#modal-body-share-'+id).html('<b>Link share:</b> <span id="link_share_'+id+'">'+ url_link + data.key + '</span>')
                $('#modal-share-'+id).modal();
            }
        });
    }
    function copyShare(id) {
        var copyText = document.getElementById("link_share_"+id);
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
    function endCourse(id,type,status) {
        $('#modal-end-course').modal();
        if (status == '2') {
            $('.modal_body_notification').html(`<h3>Khóa học này đã hết hạn đăng ký. Vui lòng liên hệ Trung tâm đào tạo</h3>`);
            $('.modal_title_notification').html(`<span>Hết hạn đăng ký`);
        } else if (status == '3') {
            $('.modal_body_notification').html(`<h3>Khóa học đã tổ chức xong/ kết thúc. Vui lòng liên hệ Trung tâm đào tạo</h3>`);
            $('.modal_title_notification').html(`<span>Khóa học kết thúc`);
        } else {
            $('.modal_body_notification').html(`<h3>Khóa học đang chờ duyệt. Vui lòng liên hệ Trung tâm đào tạo</h3>`);
            $('.modal_title_notification').html(`<span>Khóa học đang chờ duyệt`);
        }
    }
</script>
