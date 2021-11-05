
@extends('layouts.backend')

@section('page_title', $page_title)

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.management') }} <i class="uil uil-angle-right"></i>
            <a href="{{route('backend.evaluationform.manager')}}">{{ trans('backend.evaluation_form') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.plan_app.template') }}">{{ trans('backend.evaluate_training_effectiveness_form') }}</a>
            <i class="uil uil-angle-right"></i>
            <span class="font-weight-bold">{{ $page_title }}</span>
        </h2>
    </div>
@endsection

@section('content')


<div role="main">
    <form method="post" action="{{ route('module.plan_app.template.save') }}" class="form-horizontal form-ajax" role="form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4 text-right">
                <div class="btn-group act-btns">
                    @canany(['plan-app-template-create', 'plan-app-template-edit'])
                    <button type="submit" class="btn btn-primary" data-must-checked="false"><i class="fa fa-save"></i> &nbsp;{{ trans('backend.save') }}</button>
                    @endcanany
                    <a href="{{ route('module.plan_app.template') }}" class="btn btn-warning"><i class="fa fa-times-circle"></i> {{ trans('backend.back') }}</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <br>
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="active"><a href="#base" role="tab" data-toggle="tab">{{ trans('backend.info') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group row">
                                <div class="col-sm-3 control-label">
                                    <label>{{ trans('backend.evaluation_form') }}</label><span style="color:red"> * </span>
                                </div>
                                <div class="col-md-9">
                                    <input name="name" type="text" class="form-control" value="{{ $model->name }}">
                                </div>
                            </div>
                            <div id="wrap-category">
                            @foreach($cate as $item=>$value)
                                @php
                                    $plan_item = isset($cate[$item])? \Modules\PlanApp\Http\Controllers\PlanAppController::getPlanAppItem($cate[$item]->id):null;
                                @endphp
                                <div class="cate-item">
                                    <div class="form-group row">
                                        <div class="col-sm-3 control-label category">
                                            <label>{{trans('backend.topic')}} {{$item+1}}</label><span style="color:red"> * </span>
                                            <input type="hidden" name="cate_id[]" value="{{$value->id}}" />
                                        </div>
                                        <div class="col-md-9">
                                            <input name="cate[{{$item+1}}]" type="text" class="form-control" placeholder="{{trans('backend.topic')}} {{$item+1}}" value="@if(isset($cate[$item])) {{$cate[$item]->name}} @endif">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                    <div class="col-sm-3 control-label">

                                    </div>
                                    <div class="col-md-9">
                                        <div style="font-weight: bold; border-bottom: 1px solid #ccc">{{trans('backend.heading_fields')}} {{$item+1}}</div>
                                        <table class="table table-sm table-borderless border-0">
                                            <thead>
                                            <tr><th style="width: 5%;text-align: center">STT</th>
                                                <th style="width: 70%;">{{trans('backend.titles')}}</th>
                                                <th>{{trans('backend.data_type')}}</th>
                                            </tr></thead>
                                            <tbody>
                                            <tr>
                                                <td style="text-align: center">1</td>
                                                <td><input name="item[{!! $item+1 !!}][1]" value="@if(isset($plan_item[0])) {{$plan_item[0]->name}} @endif" class="form-control" placeholder="{{trans('backend.target')}}"></td>
                                                <td>
                                                    <select readonly name="type[{{$item+1}}][1]"  class="form-control">
                                                        <option value="1" @if(isset($plan_item[0])) @if($plan_item[0]->data_type==1) selected @endif  @endif>Text</option>
                                                        {{--<option value="2" @if(isset($plan_item[0])) @if($plan_item[0]->data_type==2) selected @endif  @endif>Int</option>--}}
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center">2</td>
                                                <td><input name="item[{!! $item+1 !!}][2]" value="@if(isset($plan_item[1])) {{$plan_item[1]->name}} @endif" class="form-control" placeholder="{{trans('backend.expected_results')}}"></td>
                                                <td>
                                                    <select name="type[{{$item+1}}][2]"  class="form-control">
                                                        <option value="1" @if(isset($plan_item[1])) @if($plan_item[1]->data_type==1) selected @endif  @endif>Text</option>
                                                        <option value="2" @if(isset($plan_item[1])) @if($plan_item[1]->data_type==2) selected @endif  @endif>Int</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center">3</td>
                                                <td><input name="item[{!! $item+1 !!}][3]" value="@if(isset($plan_item[2])) {{$plan_item[2]->name}} @endif" class="form-control" placeholder="{{trans('backend.unit')}}"></td>
                                                <td>
                                                    <select name="type[{{$item+1}}][3]"  class="form-control">
                                                        <option value="1" @if(isset($plan_item[2])) @if($plan_item[2]->data_type==1) selected @endif  @endif>Text</option>
                                                        <option value="2" @if(isset($plan_item[2])) @if($plan_item[2]->data_type==2) selected @endif  @endif>Int</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: center">4</td>
                                                <td><input name="item[{{$item+1}}][4]" value="@if(isset($plan_item[3])) {{$plan_item[3]->name}} @endif" class="form-control" placeholder="{{trans('backend.execution_time')}} ({{trans('backend.month')}})"></td>
                                                <td>
                                                    <select name="type[{{$item+1}}][4]"  class="form-control">
                                                        <option value="1" @if(isset($plan_item[3])) @if($plan_item[3]->data_type==1) selected @endif  @endif>Text</option>
                                                        <option value="2" @if(isset($plan_item[3])) @if($plan_item[3]->data_type==2) selected @endif  @endif>Int</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                </div>
                            @endforeach
                            </div>
                            <div class="form-group row">
                                <div class="control-label col-sm-3"></div>
                                <div class="col-md-9">
                                    <button type="button" class="btn btn-primary btnAdd">{{trans('backend.add_new')}}</button>
                                    <button type="button" class="btn btn-primary btnDel">{{trans('backend.delete')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script id="template" type="text/html">
    <div class="cate-item">
        <div class="form-group row">
            <div class="col-sm-3 control-label category">
                <label>{{trans('backend.topic')}} {index}</label><span style="color:red"> * </span>
            </div>
            <div class="col-md-9">
                <input name="cate[{index}]" type="text" class="form-control" placeholder="{{trans('backend.topic')}} {index}" value="">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3 control-label">

            </div>
            <div class="col-md-9">
                <div style="font-weight: bold; border-bottom: 1px solid #ccc">Các trường của đề mục <span></span></div>
                <table class="table table-sm table-borderless border-0">
                    <thead>
                    <tr><th style="width: 5%;text-align: center">STT</th>
                        <th style="width: 70%;">{{trans('backend.titles')}}</th>
                        <th>{{trans('backend.data_type')}}</th>
                    </tr></thead>
                    <tbody>
                    <tr>
                        <td style="text-align: center">1</td>
                        <td><input name="item[{index}][1]" value="" class="form-control" placeholder="Tiêu chí 1"></td>
                        <td>
                            <select readonly name="type[{index}][1]"  class="form-control">
                                <option value="1">Text</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">2</td>
                        <td><input name="item[{index}][2]" value="" class="form-control" placeholder="Tiêu chí 2"></td>
                        <td>
                            <select name="type[{index}][2]"  class="form-control">
                                <option value="1" >Text</option>
                                <option value="2" >Int</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">3</td>
                        <td><input name="item[{index}][3]" value="" class="form-control" placeholder="Tiêu chí 3"></td>
                        <td>
                            <select name="type[{index}][3]"  class="form-control">
                                <option value="1" >Text</option>
                                <option value="2" >Int</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">4</td>
                        <td><input name="item[{index}][4]" value="" class="form-control" placeholder="Tiêu chí 4"></td>
                        <td>
                            <select name="type[{index}][4]"  class="form-control">
                                <option value="1" >Text</option>
                                <option value="2" >Int</option>
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</script>

@stop
@section('header')
    <script src="{{asset('/styles/module/planapp/js/plan_app.js')}} "></script>
@endsection
