@extends('layouts.backend')

@section('page_title', 'Lập Đánh giá hiệu quả đào tạo')
@section('header')
    <script language="javascript" src="{{ asset('styles/module/planapp/js/plan_app.js?v'.time()) }}"></script>
@endsection
@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.training') }} <i class="uil uil-angle-right"></i>
            <a href="{{route('module.plan_app.course')}}">Quản lý Đánh giá hiệu quả đào tạo</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.plan_app.user',['course'=>$course_id,'type'=>$course_type]) }}">Đánh giá hiệu quả đào tạo của nhân viên</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection
@php
    $planCateNum = count($plan_app_template_cate);
@endphp
@section('content')

    <div class="container-fluid" id="trainingroadmap">
        <form name="frmPlanApp" method="post" action="{{route('module.plan_app.user.form', ['id' => $plan_app->id, 'user' => $profile->user_id])}}" class="form-validate">
            <div class="planappform">
                <div align="center"><h2>Kế hoạch ứng dụng sau đào tạo</h2></div>
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
                                <td>{{trans('backend.year_of_birth')}}</td>
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
                    <div class="col-md-12">
                        <h6><b>{!! $item->sort !!}. {!! ucfirst($item->name) !!}</b></h6>
                    </div>
                    </div>
                    @php
                        $plan_item = Modules\PlanApp\Http\Controllers\PlanAppController::getPlanAppItem($item->id);
                        $plan_item_user = Modules\PlanApp\Http\Controllers\PlanAppController::getPlanAppItemUser($item->id,$user_id);
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-sm tblBinding_{{$item->id}}" data-cate="{{$item->id}}">
                            <thead>
                            <tr>
                                @foreach($plan_item as $value)
                                <th>{!! $value->name !!}</th>
                                @endforeach
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($plan_item_user)>0)
                                @foreach($plan_item_user as $index => $value)
                                <tr>
                                    <td><input readonly type="text" class="form-control" name="name[{{$item->id}}][]" value="{{$value->name}}"></td>
                                    <td><input readonly type="{{$plan_item[1]->data_type==1?'text':'number'}}" class="form-control" name="item_1[{{$item->id}}][]" value="{{$value->criteria_1}}"></td>
                                    <td><input readonly type="{{$plan_item[2]->data_type==1?'text':'number'}}" class="form-control" name="item_2[{{$item->id}}][]" value="{{$value->criteria_2}}"></td>
                                    <td><input readonly type="{{$plan_item[3]->data_type==1?'text':'number'}}" class="form-control" name="item_3[{{$item->id}}][]" value="{{$value->criteria_3}}"></td>
                                    <td>
                                        <input type="hidden" value="{{$value->id}}" name="item_id[{{$item->id}}][]">
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-md-12">
                        <h6><b>{{$planCateNum+1}}. Đề xuất kiến nghị khác</b></h6>
                        <div class="form-group">
                            <textarea readonly class="form-control" name="suggest_self" id="" rows="3">{{$plan_app->suggest_self}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h6><b>{{$planCateNum+2}}. {{ trans('backend.assessments') }}</b></h6>
                    </div>
                </div>

                <div class="text-center">
                    @if($visiable=='visiable')
                    <button type="submit" class="btn btn-primary" name="btn_save" value="approved"><i class="fa fa-check"></i> {{trans('backend.approve')}}</button>
                    <button type="submit" class="btn btn-danger" name="btn_save" value="deny"><i class="fa fa-times"></i> {{trans('backend.deny')}}</button>
                    @endif
                    <a href="{{ route('module.plan_app.user',['course'=>$course_id,'type'=>$course_type]) }}" class="btn btn-info"><i class="fa fa-reply"></i> {{trans('backend.back')}}</a>
                </div>
            </div>
        </form>
    </div>
@stop
