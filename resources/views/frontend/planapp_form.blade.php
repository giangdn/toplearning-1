@extends('layouts.app')

@section('page_title', 'Lập đánh giá hiệu quả đào tạo')

@section('header')
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/profile.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styles/css/frontend/prism.css') }}">
    <script language="javascript" src="{{ asset('styles/module/planapp/js/plan_app.js') }}"></script>
@endsection

@section('content')
    <div class="container-fluid" id="trainingroadmap">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content forum-container">
                    <h2 class="st_title"><i class="uil uil-apps"></i>
                        <a href="{{ route('frontend.plan_app') }}">@lang('backend.plan_app')</a>
                        <i class="uil uil-angle-right"></i>
                        <span class="font-weight-bold">Lập đánh giá</span>
                    </h2>
                </div>
            </div>
        </div>
        <p></p>

        <form name="frmPlanApp" method="post" action="{{route('frontend.plan_app.form', ['course' => $course->id, 'type' => $course->course_type])}}" class="form-validate form-ajax">
            <div class="planappform">
                <div align="center"><h2>Đánh giá hiệu quả đào tạo</h2></div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                            <tr>
                                <td scope="row">{{trans('backend.training_program')}}</td>
                                <td>{{$course->name}}</td>
                                <td>{{trans('backend.locations')}}</td>
                                <td>{{$course->training_location_name}}</td>
                            </tr>
                            <tr>
                                <td scope="row">Thời gian từ ngày:</td>
                                <td>{{ get_date($course->start_date) . ($course->end_date ? ' - '.get_date($course->end_date) : '') }}</td>
                                <td>{{ trans('backend.organizational_units') }}</td>
                                <td>{{$course->training_unit}}</td>
                            </tr>
                            <tr>
                                <td scope="row">{{ trans('backend.employee_name') }}:</td>
                                <td>{{$profile->full_name}} ({{$profile->code}})</td>
                                <td>Năm sinh</td>
                                <td>{{get_date($profile->dob)}}</td>
                            </tr>
                            <tr>
                                <td scope="row">{{ trans('backend.title') }}:</td>
                                <td>{{$profile->title_name}}</td>
                                <td>{{ trans('backend.business_unit_name') }}</td>
                                <td>{{$profile->unit_name}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                @foreach($plan_app_template_cate as $item)
                    <div class="row">
                        <div class="col-md-8">
                            <h5>{!! $item->sort !!}. {!! ucfirst($item->name) !!}</h5>
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
                                    <td><input type="text" class="form-control" name="name[{{$item->id}}][]" value="{{$value->name}}"></td>
                                    <td><input type="{{$plan_item[1]->data_type==1?'text':'number'}}" class="form-control" name="item_1[{{$item->id}}][]" value="{{$value->criteria_1}}"></td>
                                    <td><input type="{{$plan_item[2]->data_type==1?'text':'number'}}" class="form-control" name="item_2[{{$item->id}}][]" value="{{$value->criteria_2}}"></td>
                                    <td><input type="{{$plan_item[3]->data_type==1?'text':'number'}}" class="form-control" name="item_3[{{$item->id}}][]" value="{{$value->criteria_3}}"></td>
                                    <td>
                                        <input type="hidden" value="{{$value->id}}" name="item_id[{{$item->id}}][]">
                                        @if($index>0 && $enable!='disabled')
                                            <a href="javascript:void(0)" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    @foreach($plan_item as $value)
                                        @if($value->sort==1)
                                            <td><input type="{{$value->data_type==1?'text':'number'}}" class="form-control" name="name[{{$item->id}}][]"></td>
                                        @else
                                            <td><input type="{{$value->data_type==1?'text':'number'}}" class="form-control" name="item_{{$value->sort-1}}[{{$item->id}}][]"></td>
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

    <script type="text/javascript">
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
@stop
