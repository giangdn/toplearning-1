@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.action_plan'))

@section('content')
    <div class="container" id="trainingroadmap">
        <form name="frmPlanApp" method="post" action="{{route('frontend.plan_app.form', ['course' => $course->id, 'type' => $course->course_type])}}" class="form-validate form-ajax">
            <div class="planappform">
                <div class="row pb-2 mb-2 border-bottom bg-white">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                        {{trans('backend.training_program')}}: {{ $course->name }}
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                        Địa điểm: {{ $course->training_location_name }}
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                        Thời gian: {{ get_date($course->start_date) . ($course->end_date ? ' - '.get_date($course->end_date) : '') }}
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                        Đơn vị tổ chức: {{ $course->training_unit }}
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                        Họ tên nhân viên: {{ $profile->full_name }} ({{  $profile->code }})
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                        Năm sinh: {{ get_date($profile->dob) }}
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                        Chức danh: {{ $profile->title_name }}
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
                        Đơn vị: {{ $profile->unit_name }}
                    </div>
                </div>

                @foreach($plan_app_template_cate as $item)
                    <div class="row">
                        <div class="col-md-8">
                            <h6>{!! $item->sort !!}. {!! ucfirst($item->name) !!}</h6>
                        </div>
                        <div class="col-md-4 pull-right text-right pb-1">
                            <a data-cate="{{$item->id}}" class="add_item btn btn-primary {{$enable}}"><i class=" fa fa-plus"></i> Thêm tiêu chí</a>
                        </div>
                    </div>
                    @php
                        $plan_item = App\Http\Controllers\Frontend\PlanAppController::getPlanAppItem($item->id);
                        $plan_item_user = App\Http\Controllers\Frontend\PlanAppController::getPlanAppItemUser($item->id);
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-sm tblBinding_{{$item->id}}" data-cate="{{$item->id}}">
                            <thead>
                            <tr>
                                @foreach($plan_item as $value)
                                <th class="text-center">{!! $value->name !!}</th>
                                @endforeach
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($plan_item_user)>0)
                                @foreach($plan_item_user as $index => $value)
                                <tr>
                                    <td><input type="text" class="border-0" name="name[{{$item->id}}][]" value="{{$value->name}}"></td>
                                    <td><input type="{{$plan_item[1]->data_type==1?'text':'number'}}" class="border-0" name="item_1[{{$item->id}}][]" value="{{$value->criteria_1}}"></td>
                                    <td><input type="{{$plan_item[2]->data_type==1?'text':'number'}}" class="border-0" name="item_2[{{$item->id}}][]" value="{{$value->criteria_2}}"></td>
                                    <td><input type="{{$plan_item[3]->data_type==1?'text':'number'}}" class="border-0" name="item_3[{{$item->id}}][]" value="{{$value->criteria_3}}"></td>
                                    <td>
                                        <input type="hidden" value="{{$value->id}}" name="item_id[{{$item->id}}][]">
                                        @if($index>0 && $enable!='disabled')
                                            <a href="javascript:void(0)" data-id="{{$value->id}}"><i class="material-icons">delete</i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    @foreach($plan_item as $value)
                                        @if($value->sort==1)
                                            <td><input type="{{$value->data_type==1?'text':'number'}}" class="border-0" name="name[{{$item->id}}][]"></td>
                                        @else
                                            <td><input type="{{$value->data_type==1?'text':'number'}}" class="border-0" name="item_{{$value->sort-1}}[{{$item->id}}][]"></td>
                                        @endif
                                    @endforeach
                                        <td></td>
                                </tr>
                            @endif

                            </tbody>
                        </table>
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-md-12">
                        <h6><b>4. Đề xuất, kiến nghị khác (nếu có):</b></h6>
                        <div class="form-group">
                            <textarea class="form-control" name="suggest_self" rows="3">{{$plan_app ? $plan_app->suggest_self : ''}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    @if($enable!='disabled')
                    <button type="submit" class="btn btn-primary" name="btn_save" value="1"><i class="fa fa-floppy-o"></i> Cập nhật</button>
                    <button type="submit" class="btn btn-primary" name="btn_save" value="2" id="send-mail-approve">
                        <i class="fa fa-paper-plane"></i> Cập nhật & gửi
                    </button>
                        <input type="hidden" name="user_id" value="{{ $profile->user_id }}">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <input type="hidden" name="course_type" value="{{ $course->course_type }}">
                    @endif
                    <a href="{{route('frontend.plan_app')}}" class="btn btn-info"><i class="fa fa-reply"></i> Trở về</a>
                </div>
            </div>
        </form>
    </div>
    <br>
@stop
@section('footer')
    <script type="text/javascript">
        $('button[type=submit]').on('click', function () {
            event.preventDefault();
            var form = $(this).closest('form');
            var formData = new FormData(form[0]);
            var btn = $(this);
            var btn_text = $(this).html();
            // btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
            if ($(this).attr('name')) {
                formData.append($(this).attr('name'),$(this).val());
            }
            btn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i>Đang lưu ...');
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                dataType: 'json',
                data: formData,
                cache:false,
                contentType: false,
                processData: false
            }).done(function(data) {
                btn.prop('disabled', false).html(btn_text);
                show_message(
                    data.message,
                    data.status
                );

                if (data.redirect) {
                    setTimeout(function () {
                        window.location = data.redirect;
                    }, 1000);
                    return false;
                }
                if (data.status === "error") {
                    return false;
                }
                return false;
            }).fail(function(data) {
                btn.prop('disabled', false).html(btn_text);
                show_message('Lỗi dữ liệu', 'error');
                return false;
            });
        });
        $('.add_item').on('click',function (e) {
            let instance = $(this).data('cate');
            e.preventDefault();
            let html="";
            html+='<tr>\
            <td><input type="text" class="border-0" name="name['+instance+'][]"></td>\
            <td><input type="text" class="border-0" name="item_1['+instance+'][]"></td>\
            <td><input type="text" class="border-0" name="item_2['+instance+'][]"></td>\
            <td>\
                <input type="text" class="border-0" name="item_3['+instance+'][]">\
            </td>\
            <td><a href="javascript:void(0)"><i class="material-icons">delete</i></a></td>\
            </tr>';
            $('.tblBinding_'+instance+' tbody').append(html);
        });
        $('table').on('click','a',function (e) {
            let id =parseInt($(this).data('id')),
                $this=$(this);
            if (isNaN(id))
                $(this).closest('tr').remove();
            else {
                let icon = $(this),
                    origin_icon = icon.html();
                icon.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
                $.ajax({
                    type: 'post',
                    url: base_url + '/plan-app/delete',
                    dataType: 'json',
                    data: {'id': id}
                }).done(function (data) {
                    $this.closest('tr').remove();
                    icon.prop('disabled', false).html(origin_icon);
                }).fail(function (data) {
                    icon.prop('disabled', false).html(origin_icon);
                    return false;
                });
            }
        });
        $("#send-mail-approve").on('click', function () {
            var user_id = $("input[name=user_id]").val();
            var course_id = $("input[name=course_id]").val();
            var course_type = $("input[name=course_type]").val();
            $.ajax({
                url: base_url +'/plan-app/send-mail-approve',
                type: 'post',
                data: {
                    user_id: user_id,
                    course_id: course_id,
                    course_type: course_type,
                }
            }).done(function(data) {
                return false;
            }).fail(function(data) {
                show_message('Lỗi hệ thống', 'error');
                return false;
            });
        });
    </script>
@endsection
