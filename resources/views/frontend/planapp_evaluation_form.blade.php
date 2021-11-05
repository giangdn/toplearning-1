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
                    </h2>
                </div>
            </div>
        </div>
        <p></p>
        <form name="frmPlanApp" method="post" action="{{route('frontend.plan_app.form.evaluation', ['course' => $course->id, 'type' =>$course->course_type])}}" class="form-validate form-ajax">
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
                                <td>{{get_date($course->start_date)}} - {{get_date($course->end_date)}}</td>
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
                <div class="row">
                    <div class="col-md-12">
                        <h5><b>I. Phần dành cho học viên tự đánh giá</b></h5>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-8">
                        <ol>
                            <li>
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="self" value="1" {{$plan_app->evaluation_self==1?'checked':''}} >
                                    Đạt trên 80% mục tiêu.
                                </label>
                            </li>
                            <li>
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="self" value="2" {{$plan_app->evaluation_self==2?'checked':''}}>
                                    Đạt trên 50% mục tiêu.
                                </label>
                            </li>
                            <li>
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="self" value="3" {{$plan_app->evaluation_self==3?'checked':''}}>
                                    {{trans("backend.not_achieved")}} (<50%) mục tiêu.
                                </label>
                            </li>
                        </ol>
                        </div>
                    </div>
                </div>

                @foreach($plan_app_template_cate as $item)
                    <div class="row">
                        <div class="col-md-12">
                            <h6><b>{!! $item->sort !!}. {!! ucfirst($item->name) !!}</b></h6>
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
                                            <input readonly type="text" class="form-control" name="name[{{$item->id}}][]" value="{{$value->name}}">
                                            <input type="hidden" value="{{$value->id}}" name="item_id[{{$item->id}}][]">
                                        </td>
                                        <td><input type="text" class="form-control" name="result[{{$item->id}}][]" value="{{$value->result}}"></td>
                                        <td><input type="text" class="form-control is-number" name="finish[{{$item->id}}][]" value="{{$value->finish}}"></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endforeach

                <div class="row">
                    <div class="col-md-12">
                        <h5><b>II. Phần dành cho Trưởng đơn vị đánh giá</b></h5>
                    </div>
                    <div class="col-md-12">
                        <h6><b>1. Mức độ hoàn thành mục tiêu cam kết</b></h6>
                        <div class="form-group">
                            <textarea readonly class="form-control" name="evaluation_manager" id="" rows="3" placeholder="Diễn giải cho đánh giá trên">{{$plan_app->evaluation_manager}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h6><b>2. Đề xuất, kiến nghị</b></h6>
                        <div class="form-group">
                            <textarea readonly class="form-control" name="suggest_manager" id="" rows="3" placeholder="Nhập diễn giải đề xuất">{{$plan_app->suggest_manager}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <h6><b>3. Vận dụng thực tế</b></h6>
                        <div class="form-check">
                            <label  class="form-check-label">
                                <input type="radio" class="form-check-input" name="evaluation" value="1" disabled {{ $plan_app->reality_manager==1 ?'checked':''}}>Vận dụng được vào thực tế công việc
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="evaluation" value="2" disabled {{ $plan_app->reality_manager==2 ?'checked':''}}>Chưa vận dụng được vào thực tế công việc
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-3"><label>Lý do</label></div>
                            <div class="col-md-9">
                                <textarea class="form-control"  disabled name="reason" id="reason" rows="3"> {{ $plan_app->reason_reality_manager}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 ">
                        <h6><b>4. Kết quả</b></h6>
                        <div class="form-check">
                            <label  class="form-check-label">
                                <input type="radio" class="form-check-input" disabled name="result" value="1" {{ ($plan_app->result==1 || ! $plan_app->result) ?'checked':''}}>{{trans("backend.achieved")}}
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" disabled name="result" value="2"  {{ $plan_app->result==2 ?'checked':''}}>{{trans("backend.not_achieved")}}
                            </label>
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
