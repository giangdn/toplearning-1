@extends('themes.mobile.layouts.app')

@section('page_title', trans('app.action_plan'))

@section('content')
    <div class="container" id="trainingroadmap">
        <form name="frmPlanApp" method="post" action="{{route('frontend.plan_app.form.evaluation', ['course' => $course->id, 'type' => $course->course_type])}}" class="form-validate form-ajax">
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
                <div class="row">
                    <div class="col-md-12">
                        <h6><b>I. Phần dành cho học viên tự đánh giá</b></h6>
                    </div>
                </div>
                    <ol style="list-style: none;" class="pl-3">
                        <li>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="self" value="1" {{$plan_app->evaluation_self==1?'checked':''}} >
                                Đạt trên 80% mục tiêu đã định trong kế hoạch ứng dụng
                            </label>
                        </li>
                        <li>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="self" value="2" {{$plan_app->evaluation_self==2?'checked':''}}>
                                Đạt trên 50% mục tiêu đã định trong kế hoạch ứng dụng
                            </label>
                        </li>
                        <li>
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="self" value="3" {{$plan_app->evaluation_self==3?'checked':''}}>
                                Không đạt (<50%) mục tiêu đã định trong kế hoạch ứng dụng
                            </label>
                        </li>
                    </ol>
                @foreach($plan_app_template_cate as $item)
                    <div class="row">
                        <div class="col-md-12">
                            <p><b>{!! $item->sort !!}. {!! ucfirst($item->name) !!}</b></p>
                        </div>
                    </div>
                    @php
                        $plan_item = App\Http\Controllers\Frontend\PlanAppController::getPlanAppItemTarget($item->id);
                        $plan_item_user = App\Http\Controllers\Frontend\PlanAppController::getPlanAppItemUser($item->id);
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-sm tblBinding_{{$item->id}}" data-cate="{{$item->id}}">
                            <thead>
                                <tr>
                                    <th>{!! $plan_item->name !!}</th>
                                    <th>Kết quả đạt được</th>
                                    <th>% {{trans("backend.finish")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($plan_item_user)>0)
                                    @foreach($plan_item_user as $index => $value)
                                    <tr>
                                        <td>
                                            <input readonly type="text" class="border-0" name="name[{{$item->id}}][]" value="{{$value->name}}">
                                            <input type="hidden" value="{{$value->id}}" name="item_id[{{$item->id}}][]">
                                        </td>
                                        <td><input type="text" class="border-0" name="result[{{$item->id}}][]" value="{{$value->result}}"></td>
                                        <td><input type="text" class="border-0 is-number" name="finish[{{$item->id}}][]" value="{{$value->finish}}"></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endforeach

                <div class="row">
                    <div class="col-md-12">
                        <h6><b>II. Phần dành cho Trưởng đơn vị đánh giá</b></h6>
                    </div>
                    <div class="col-md-12">
                        <p><b>1. Mức độ hoàn thành mục tiêu cam kết</b></p>
                        <div class="form-group">
                            <textarea readonly class="form-control" name="evaluation_manager" id="" rows="3" placeholder="Diễn giải cho đánh giá trên">{{$plan_app->evaluation_manager}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <p><b>2. Đề xuất, kiến nghị</b></p>
                        <div class="form-group">
                            <textarea readonly class="form-control" name="suggest_manager" id="" rows="3" placeholder="Nhập diễn giải đề xuất">{{$plan_app->suggest_manager}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    @if($visiable=='visiable')
                    <button type="submit" class="btn btn-primary" name="btn_save" value="1"><i class="fa fa-floppy-o"></i> Cập nhật</button>
                    <button type="submit" class="btn btn-primary" name="btn_save" value="2"><i class="fa fa-paper-plane"></i> Cập nhật & gửi</button>
                    @endif
                    <a href="{{route('frontend.plan_app')}}" class="btn btn-info"><i class="fa fa-reply"></i> Trở về</a>
                </div>
            </div>
        </form>
    </div>
    <br>
@stop
@section('footer')
    <script type="text/javascript" src="{{ asset('styles/module/planapp/js/plan_app.js') }}"></script>
@endsection
