@php
    $url = $type == 1 ? route('module.online.detail', ['id' => $item->id]) : route('module.offline.detail', ['id' => $item->id]);
    $item->getStatus();
@endphp
<div class="fcrse_1 mb-20">
    <a href="{{ $url }}" class="fcrse_img">
        <img class="picture_course" src="{{ image_file($item->image) }}" alt="">
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
            <div class="badge_seller">
                {{ $point }} 
                <img class="point ml-1" style="width: 20px;height: 20px" src="{{ asset('styles/images/level/point.png') }}" alt="">
            </div>
            @endif
            <div class="crse_reviews">
                <i class='uil uil-star'></i>{{ $item->avgRatingStar() }}
            </div>
        </div>
    </a>
    <div class="fcrse_content">
        <div class="eps_dots more_dropdown check_course">
            <a href="javascript:void(0)"><i class='uil uil-ellipsis-v'></i></a>
            <div class="dropdown-content">
                <span>
                    <i class='uil uil-heart-alt'></i>
                    @if ($item->bookmarked)
                        <a href="{{ route('frontend.home.remove_course_bookmark',['course_id'=>$item->id,'course_type'=>$type, 'my_course'=> 0]) }}" class="item-bookmark">
                        @lang('app.unbookmark')
                    </a>
                    @else
                    <a href="{{ route('frontend.home.save_course_bookmark',['course_id'=>$item->id,'course_type'=>$type, 'my_course' => 0]) }}" class="item-bookmark">
                        @lang('app.bookmark')
                    </a>
                    @endif
                </span>
                @php
                    $check_promotion_course_setting = \Modules\Promotion\Entities\PromotionCourseSetting::where('course_id',$item->id)->exists();
                @endphp
                @if ($check_promotion_course_setting)
                    <span onclick="openModalBonus({{$item->id}})">
                        <img class="image_bonus_courses" src="{{asset("images/level/point.png")}}" alt="" width="29px" height="15px">
                        Điểm thưởng
                    </span>
                @endif
                <span href="javascript:void(0)" style="cursor: pointer" class="ml-1" onclick="shareCourse({{$item->id}})">
                    <i class="fas fa-link mr-2"></i> 
                    Share
                </span>
            </div>
        </div>
        <div class="vdtodt">
            <span class="vdt14"><i class="uil uil-windsock"></i>{{ $item->register->count() }} @lang('app.joined')</span>
            <span class="vdt14"><i class='uil uil-heart {{ $item->bookmarked ? 'check-heart' : ''}}'></i> {{ $item->bookmarked ? __('app.bookmarked') : __('app.bookmark') }}</span>
        </div>
        <div class="course_names">
            <a href="{{ $url }}" class="crse14s course_name">{{ $item->name }}</a>
            <span class="hidden_name">{{ $item->name }}</span>
        </div>
        <div class="vdtodt">
            <span class="vdt14"><b>Mã khóa học:</b> {{$item->code}}</span>
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
        <div class="vdtodt" onclick="openModalObject({{$item->id}})" style="cursor: pointer">
            <p class="cr1fot import-plan"><b>Đối tượng:</b> <i title="{{ $item->getStatus() }}">Chi tiết</i></p>
        </div>
            <p class="cr1fot"><a href="#">{{ $item->subject_name }}</a></p>
        @php
            $status = $item->getStatusRegister();
            $text = status_register_text($status);
        @endphp
        <div class="auth1lnkprce">
            <div class="row">
                <div class="col-4 chart">
                    @if ($status == 4)
                        <canvas id="chartProgress_{{$item->id}}" width="80px" height="80px"></canvas>
                    @endif
                </div>
                <div class="prce142 col-8 button_course">
                    @php
                        $percent = \Modules\Online\Entities\OnlineCourse::percentCompleteCourseByUser($item->id, \Auth::id());
                    @endphp                
                    @if($status == 1)
                        <div class="mt-2 item item-btn">
                            <button data-toggle="modal" data-target="#modal-referer-{{$item->id}}" id="btn_register_{{$item->id}}" class="btn btn_adcart">{{ $text }}</button>
                        </div>
                    @elseif($status == 4)
                        <div class="mt-2">
                            <button onclick="window.location.href='{{ $url }}'" class="btn btn_adcart">Vào học</button>
                        </div>
                    @else
                        <div class="mt-2">
                            <button type="button" class="btn btn_adcart">{{ $text }}</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MOdal SHOW ĐỐI TƯỢNG --}}
<div class="modal fade" id="modal_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="" method="post" class="form-ajax">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel_{{$item->id}}">Đối tượng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="tDefault table table-hover bootstrap-table" id="table_object_{{$item->id}}">
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

{{-- MODAL ĐĂNG KÝ --}}
<div class="modal fade modal-add-activity" id="modal-referer-{{$item->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('backend.add_presenter') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            @if ($type == 1)
                <form action="{{ route('module.online.register_course', ['id' => $item->id]) }}" id="frm-course-{{$item->id}}" method="post" class="form-ajax">
            @else
                <form action="{{ route('module.offline.register_course', ['id' => $item->id]) }}" id="frm-course-{{$item->id}}" method="post" class="form-ajax">
            @endif
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 block-left">
                        <label>{{ trans('backend.presenter_code') }}</label>
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="referer" id="referer_{{$item->id}}" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 block-left">
                    </div>
                    <div class="col-md-7">
                        <a href="javascript:void(0)" id="referer_modal_{{$item->id}}" class="load-modal" data-url="{{ route('frontend.online.referer.show_modal',[$item->id] ) }}"><img src="{{asset('images/qr-code.svg')}}" width="30px" /> {{ trans('backend.scan_referrer_code') }}</a>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><i class="fa fa-plus-circle"></i> {{ trans('backend.add_presenter') }}</button>
                <button type="button" onclick="closeModal({{$item->id}})" class="btn btn-danger" ><i class="fa fa-times-circle"></i> {{ trans('backend.cancel') }}</button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL ĐIỂM THƯỞNG --}}
<div class="modal fade modal-add-activity" id="modal-bonus-{{$item->id}}">
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
                    $complete = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($item->id, 1, 'complete');
                    $landmarks = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($item->id, 1, 'landmarks');
                    $rating_star = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($item->id, 1, 'rating_star');
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
                                        <input type="radio" class="custom-control-input point-type" id="promotion_0_{{$item->id}}" onclick="checkBoxBonus({{$item->id}})" name="method" value="0">
                                        <label class="custom-control-label" for="promotion_0_{{$item->id}}">{{ trans('backend.complete_course') }}</label>
                                    </div>
                                </div>
                            @endif
                            @if ($landmarks)
                                <div class="form-check form-check-inline promotion_1_radio">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input point-type" id="promotion_1_{{$item->id}}" onclick="checkBoxBonus({{$item->id}})" name="method" value="1">
                                        <label class="custom-control-label" for="promotion_1_{{$item->id}}">{{ trans('backend.landmarks') }}</label>
                                    </div>
                                </div>
                            @endif
                            @if ($rating_star)
                                <div class="form-check form-check-inline promotion_2_radio">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input point-type" id="promotion_2_{{$item->id}}" onclick="checkBoxBonus({{$item->id}})" name="method" value="2">
                                        <label class="custom-control-label" for="promotion_2_{{$item->id}}">{{ trans('backend.other') }}</label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if ($complete)
                        <div class="promotion_0_group_{{$item->id}}">
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
                        <div class="promotion_1_group_{{$item->id}}">
                            <div class="row promotion-table">
                                <div class="col-md-12">
                                    <table class="tDefault table table-hover bootstrap-table" id="table_setting_{{$item->id}}">
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

                    <div class="promotion_2_group_{{$item->id}}">
                        @foreach($arr_code as $key => $code)
                            @php
                                $other = \Modules\Promotion\Entities\PromotionCourseSetting::getPromotionCourseSetting($item->id, 1, $key);
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

{{-- MODAL LINK SHARE --}}
<div class="modal fade modal-add-activity" id="modal-share-{{$item->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Share link khóa học</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modal-body-share-{{$item->id}}">
                {{-- @if($promotion_share)
                    <b>Link share:</b> {{ route('module.online.detail', ['id' => $item->id]).'?share_key='. $promotion_share->share_key }}
                @endif --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="copyShare({{$item->id}})">Copy</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    function type_formatter(value, row, index) {
        return value == 1 ? 'Bắt buộc' : '{{ trans("backend.register") }}';
    }

    function stt_formatter(value, row, index) {
        return (index + 1);
    }

    function openModalObject(id) {
        $('#modal_'+id).modal();
        var url = "{{ route('module.online.get_object', ':id') }}";
        url = url.replace(':id',id);
        var table_object = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: url,
            table: '#table_object_'+id,
        });
    }
    
    function closeModal(id) {
        $('#referer_'+id).val('');
        var form =  $('#frm-course-'+id);
        form.submit();
    }

    var percent = '<?php echo $percent ?>';
    var status = '<?php echo $status ?>';
    var id = '<?php echo $item->id ?>';
    if (percent >= 0 && status == 4) {
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

    // Điểm thưởng
    function stt_formatter_bonus(value, row, index) {
        return (index + 1);
    }
    
    function openModalBonus(id) {
        $('#modal-bonus-'+id).modal();
    }

    var complete = '<?php echo $complete  ?>';
    var landmarks = '<?php echo $landmarks  ?>';
    var other = '<?php echo $other ?>';
    if (landmarks !== '' && other == '' && complete == '') {
        $(".promotion_0_group_{{$item->id}}").hide();
        $(".promotion_1_group_{{$item->id}}").show();
        $(".promotion_2_group_{{$item->id}}").hide();
    } else if (landmarks == '' && other !== '' && complete == '') {
        $(".promotion_0_group_{{$item->id}}").hide();
        $(".promotion_1_group_{{$item->id}}").hide();
        $(".promotion_2_group_{{$item->id}}").show();
    } else {
        $(".promotion_0_group_{{$item->id}}").show();
        $(".promotion_1_group_{{$item->id}}").hide();
        $(".promotion_2_group_{{$item->id}}").hide();
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
</script>
