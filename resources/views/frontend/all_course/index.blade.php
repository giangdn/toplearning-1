@extends('layouts.app')

@section('page_title', 'Tất cả khóa học')

@section('header')

@endsection

@section('content')
    <link rel="stylesheet" href="{{ asset('css/all_course.css') }}">
    @php
        $search_status = request()->get('status');
        $search_course_type = request()->get('course_type');
        $search_training_program = request()->get('training_program_id');
        $search_level_subject = request()->get('level_subject_id');
        $search_subject = request()->get('subject_id');
    @endphp
    <div class="sa4d25">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="_14d25">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ibox-content forum-container">
                                    @if (empty($course_type))
                                        <h2 class="st_title"><i class="uil uil-apps"></i>
                                            @lang('app.course')
                                        </h2>
                                    @else
                                        <h2 class="st_title"><i class="uil uil-apps"></i>
                                            <a href="{{ route('frontend.all_course',['type' => 0]) }}">@lang('app.course') </a>
                                            <i class="uil uil-angle-right"></i>
                                            <span class="font-weight-bold">
                                                @if ($course_type == 1)
                                                    Khóa học online
                                                @elseif ($course_type == 2)
                                                    Khóa học tập trung
                                                @elseif ($course_type == 3)
                                                    Khóa học của tôi
                                                @elseif ($course_type == 4)
                                                    Khóa đang học
                                                @endif
                                            </span>
                                        </h2>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-3 col-xs-6">
                                <div class="submit_course" onclick="submitCourse(1)">
                                    <div class="small-box" style="background: #8b1409;">
                                        <div class="inner text-white">
                                            <h3>{{ $count_course_online }}</h3>
                                            <p class="text-white">@lang('backend.online_course')</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-globe-americas"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-xs-6">
                                <div class="submit_course" onclick="submitCourse(2)">
                                    <div class="small-box" style="background: #FEF200">
                                        <div class="inner" style="color: #8b1409;">
                                            <h3>{{ $count_course_offline }}</h3>
                                            <p style="color: #8b1409;">@lang('backend.offline_course')</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-xs-6">
                                <div class="submit_course" onclick="submitCourse(3)">
                                    <div class="small-box" style="background: #1988C8">
                                        <div class="inner text-white">
                                            <h3>{{ $count_my_course }}</h3>
                                            <p class="text-white">@lang('app.my_course')</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-xs-6">
                                <div class="submit_course" onclick="submitCourse(4)">
                                    <div class="small-box" style="background: #0FA461">
                                        <div class="inner text-white">
                                            <h3>{{ $count_course_learning }}</h3>
                                            <p class="text-white">@lang('app.course_learning')</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('frontend.all_course_search') }}" method="GET" id="form_search_course">
                            <input type="hidden" name="course_type" id="course_type">
                            <div class="row from_online_courses">
                                <div class="col-lg-3 col-md-3 search_course">
                                    <select name="training_program_id" id="training_program_id" class="ui hj145 dropdown cntry152 prompt srch_explore load-training-program"
                                            data-placeholder="Chương trình đào tạo" onchange="submit()">
                                        @if(isset($training_program))
                                            <option value="{{ $training_program->id }}" selected> {{ $training_program->name }} </option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 search_course">
                                    <select name="level_subject_id" id="level_subject_id" class="ui hj145 dropdown cntry152 prompt srch_explore load-level-subject"
                                            data-placeholder="{{ trans('backend.type_subject') }}" data-training-program="{{ $search_training_program }}" onchange="submit()">
                                        @if(isset($level_subject))
                                            <option value="{{ $level_subject->id }}" selected> {{ $level_subject->name }} </option>
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="col-lg-3 col-md-3 search_course">
                                    <select name="status" id="status" class="select2 ui hj145 dropdown cntry152 prompt srch_explore" data-placeholder="Trạng thái">
                                        <option value="" selected disabled>Trạng thái</option>
                                        <option value="1" {{ isset($status) && $status == 1 ? 'selected' : ''}}>Đăng ký</option>
                                        <option value="2" {{ isset($status) && $status == 2 ? 'selected' : ''}}>Đang học</option>
                                        <option value="3" {{ isset($status) && $status == 3 ? 'selected' : ''}}>Chờ duyệt</option>
                                        <option value="4" {{ isset($status) && $status == 4 ? 'selected' : ''}}>Hoàn thành</option>
                                        <option value="5" {{ isset($status) && $status == 5 ? 'selected' : ''}}>Đã kết thúc</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-3 search_course">
                                    <input class="form-control" type="text" placeholder="{{ trans('app.enter_course_code_name') }}" name="search" autocomplete="off">
                                </div>

                                <div class="col-lg-3 col-md-3 search_course">
                                    <div class="ui search focus search_date_start">
                                        <div class="input-group">
                                            <div class="ui left input swdh11">
                                                <input class="prompt srch_explore datepicker" type="text" placeholder="{{ trans('app.start_date') }}" name="fromdate" autocomplete="off" style="height: 8px">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 search_course">
                                    <div class="ui search focus search_date_end">
                                        <div class="input-group">
                                            <div class="ui left input swdh11">
                                                <input class="prompt srch_explore datepicker" type="text" placeholder="{{ trans('app.end_date') }}" name="todate" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 search_course">
                                    <div class="ui search focus input_search">
                                        <div class="input-group mb-3">
                                            <div class="ui left input swdh11">
                                                <button class="btn btn-secondary" onclick="submit();">
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="_14d25 mt-1">
                                    @if (!empty($items))
                                        @if ($set_paginate == 1)
                                            <div class="row m-0">
                                                @foreach($items as $item)
                                                    <div class="col-lg-3 col-md-4 p-1">
                                                        @include('frontend.all_course.item',['type'=>$item->course_type])
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="row m-0" id="results">
                                            </div>
                                            <div class="ajax-loading text-center mb-5">
                                                <div class="spinner-border" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div> 
                                        @endif
                                    @else
                                        <div class="row">
                                            <div class="fcrse_1 mb-20">
                                                <div class="text-center">
                                                    <span>@lang('app.not_found')</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ĐĂNG KÝ --}}
    <form action="" id="frm-course" method="post" class="form-ajax">

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

    {{-- MODAL LINK SHARE --}}
    <div class="modal fade modal-add-activity" id="modal-share">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Share link khóa học</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="modal-body-share">
                </div>
                <div class="modal-footer">
                    <div id="btn-copy">
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL MÔ TẢ --}}
    <div class="modal fade modal-add-activity" id="modal-description">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Mô tả</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="modal-body-description">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('backend.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MOdal SHOW ĐỐI TƯỢNG --}}
    <div class="modal fade" id="modal_object" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="post" class="form-ajax">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Đối tượng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body model_body_object pt-0 mt-2">
                        <table class="table table-bordered table-striped" id="table_object">
                            <thead>
                                <tr>
                                    <th data-field="title_name">{{trans('backend.title')}}</th>
                                    <th data-align="center" data-field="type" data-width="10%" data-formatter="type_formatter">{{trans('backend.type_object')}}</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_object">
                                
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

    {{-- MODAL ĐIỂM THƯỞNG --}}
    <div class="modal fade modal-add-activity" id="modal-bonus">
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
                    <div class="promotion-enable">
                        <div class="form-group row">
                            <div class="col-sm-3 control-label">
                                <h6>{{ trans('backend.scoring_method') }}:</h6>
                            </div>
                            <div class="col-md-9" id="checkbox_promotion">
                                
                            </div>
                        </div>
                        
                        <div id="promotion_description">

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
        window.onload = canvasPercent;
        function canvasPercent() {
            $(".canvas_percent").each(function() {
                var value = $(this).val()
                var array_value = value.split(",");
                // console.log(array_value);
                var percent = array_value[2];
                var status = array_value[3];
                var id = array_value[0];
                var type = array_value[1];
                if (percent >= 0 && status == 4) {
                    var myChartCircle = new Chart('chartProgress_'+id+'_'+type, {
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
            });
        }

        function submitCourse(type) {
            var course_type = $('#course_type').val(type);
            $("#level_subject_id").empty();
            $("#level_subject_id").data('course_type', course_type);
            $('#level_subject_id').trigger('change');
        }

        $('#training_program_id').on('change', function () {
            var training_program_id = $('#training_program_id option:selected').val();
            $("#level_subject_id").empty();
            $("#level_subject_id").data('training-program', training_program_id);
            $('#level_subject_id').trigger('change');
        });

        $('#level_subject_id').on('change', function () {
            var training_program_id = $('#training_program_id option:selected').val();
            var level_subject_id = $('#level_subject_id option:selected').val();
            $("#subject_id").empty();
            $("#subject_id").data('training-program', training_program_id);
            $("#subject_id").data('level-subject', level_subject_id);
            $('#subject_id').trigger('change');
        });

        $('#status').on('change', function () {
            var status = $('#status option:selected').val();
            $("#level_subject_id").empty();
            $("#level_subject_id").data('status', status);
            $('#level_subject_id').trigger('change');
        });

            // Điểm thưởng
    function stt_formatter_bonus(value, row, index) {
        return (index + 1);
    }
    
    function openModalBonus(id,type) {
        $.ajax({
            type: "POST",
            url: "{{ route('frontend.ajax_bonus_course') }}",
            data:{
                id: id,
                type: type,
            },
            success: function (data) {
                $('#checkbox_promotion').html(data.html);
                $('#promotion_description').html(data.rhtml);
                if (data.landmarks !== '' && data.other == '' && data.complete == '') {
                    $(".promotion_0_group_"+id+"_"+type+"").hide();
                    $(".promotion_1_group_"+id+"_"+type+"").show();
                    $(".promotion_2_group_"+id+"_"+type+"").hide();
                } else if (data.landmarks == '' && data.other !== '' && data.complete == '') {
                    $(".promotion_0_group_"+id+"_"+type+"").hide();
                    $(".promotion_1_group_"+id+"_"+type+"").hide();
                    $(".promotion_2_group_"+id+"_"+type+"").show();
                } else {
                    $(".promotion_0_group_"+id+"_"+type+"").show();
                    $(".promotion_1_group_"+id+"_"+type+"").hide();
                    $(".promotion_2_group_"+id+"_"+type+"").hide();
                }
                $('#modal-bonus').modal();
            }
        });
    }
    
    function checkBoxBonus(id,type) {
        if ($("#promotion_0_"+id+"_"+type).is(":checked")) {
            $(".promotion_0_group_"+id+"_"+type).show();
            $(".promotion_1_group_"+id+"_"+type).hide();
            $(".promotion_2_group_"+id+"_"+type).hide();
        }

        if ($("#promotion_1_"+id+"_"+type).is(":checked")) {
            $(".promotion_0_group_"+id+"_"+type).hide();
            $(".promotion_1_group_"+id+"_"+type).show();
            $(".promotion_2_group_"+id+"_"+type).hide();
            var url = "{{ route('module.promotion.get_setting', ['courseId' => ':id', 'course_type' => 1, 'code' => 'landmarks']) }}";
            url = url.replace(':id',id);
            var table_bonus = new LoadBootstrapTable({
                locale: '{{ \App::getLocale() }}',
                url: url,
                table: '#table_setting_'+id+'_'+type
            });
        }

        if ($("#promotion_2_"+id+"_"+type).is(":checked")) {
            $(".promotion_0_group_"+id+"_"+type).hide();
            $(".promotion_1_group_"+id+"_"+type).hide();
            $(".promotion_2_group_"+id+"_"+type).show();
        }
    }

        // Share khóa học
        function shareCourse(id,type) {
            var share_key = Math.random().toString(36).substring(3);
            if (type == 1) {
                var url = "{{ route('module.online.detail.share_course', ['id' => ':id', 'type' => 1]) }}";
            } else {
                var url = "{{ route('module.offline.detail.share_course', ['id' => ':id', 'type' => 2]) }}";
            }
            url = url.replace(':id',id);
            $.ajax({
                type: "POST",
                url: url,
                data:{
                    share_key: share_key,
                },
                success: function (data) {
                    if (type == 1) {
                        var url_link = "{{ route('module.online.detail_online', ['id' => ':id']).'?share_key=' }}";
                    } else {
                        var url_link = "{{ route('module.offline.detail', ['id' => ':id']).'?share_key=' }}";
                    }
                    url_link = url_link.replace(':id',id);
                    $('#modal-body-share').html('<b>Link share:</b> <span id="link_share'+'">'+ url_link + share_key + '</span>')
                    $('#btn-copy').html('<button type="button" class="btn" onclick="copyShare('+id+','+type+')">Copy</button>')
                    $('#modal-share').modal();
                }
            });
        }

        function copyShare(id,type) {
            var copyText = document.getElementById("link_share");
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

        function openModalDescription(id,type) {
            $.ajax({
                type: "POST",
                url: "{{ route('frontend.ajax_content_course') }}",
                data:{
                    id: id,
                    type: type,
                },
                success: function (data) {
                    $('#modal-body-description').html(data)
                    $('#modal-description').modal();
                }
            });
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

        function openModalObject(id,type) {
            $.ajax({
                type: "POST",
                url: "{{ route('frontend.ajax_object_course') }}",
                data:{
                    id: id,
                    type: type,
                },
                success: function (data) {
                    let rhtml = '';
                    if (data.titles_join) {
                        $.each(data.titles_join, function (i, item){
                            rhtml +=`<tr>
                                        <td>`+ item +`</td>
                                        <td>Bắt buộc</td>
                                    </tr>`;
                        });
                    }
                    if (data.titles_recomment) {
                        $.each(data.titles_recomment, function (i, item){
                            rhtml +=`<tr>
                                        <td>`+ item +`</td>
                                        <td>Khuyến khích</td>
                                    </tr>`;
                        });
                    }
                    $('#tbody_object').html(rhtml)
                    $('#modal_object').modal();
                }
            });
        }
        
        function submitRegister(id,type) {
            var answer = window.confirm("Anh/Chị có muốn đăng ký tham gia khóa học không?");
            if (answer) {
                if (type == 1) {
                    var url_link = "{{ route('module.online.register_course', ['id' => ':id']) }}";
                } else {
                    var url_link = "{{ route('module.offline.register_course', ['id' => ':id']) }}";
                }
                url_link = url_link.replace(':id',id);
                console.log(url_link);
                $('#frm-course').attr('action', url_link);
                var form = $('#frm-course');
                form.submit();
            } 
        }

        var page = 1; 
        load_more(page); 
        $(window).scroll(function() { 
            if($(window).scrollTop() + $(window).height() >= $(document).height()) { 
                page++; 
                load_more(page);   
            }
        });     
        function load_more(page){
            $.ajax({
                url: '{{ route('frontend.all_course',['type' => $course_type]) }}' + "?page=" + page,
                type: "get",
                datatype: "html",
                beforeSend: function() 
                {
                    $('.ajax-loading').show();
                }
                })
                .done(function(data)
                {
                    if(data.length == 0){
                    console.log(data.length);
                    $('.ajax-loading').html("No more records!");
                    return;
                }
                $('.ajax-loading').hide(); 
                $("#results").append(data);   
                canvasPercent();     
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                alert('No response from server');
            });
        }
    </script>
@stop
