@extends('layouts.backend')

@section('page_title', 'Thông tin Orders')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            {{ trans('backend.study_promotion_program') }} <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.promotion.orders.buy') }}">{{ trans('backend.promotion_history') }}</a>
            <i class="uil uil-angle-right"></i>
            <span>{{ $order->orders_id }}</span>
        </h2>
    </div>
    <div role="main">
        <div class="tPanel">
            <ul class="nav nav-pills mb-4" role="tablist" id="mTab">
                <li class="nav-item"><a href="#base" class="nav-link active" role="tab" data-toggle="tab">@lang('app.information')</a></li>
            </ul>
            <div class="tab-content">
                <div id="base" class="tab-pane active">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mod-scb-profile">
                                        <div class="form-group row">
                                            <div class="col-4 col-sm-4 control-label p-0">
                                                @lang('app.code_order')
                                            </div>
                                            <div class="col-8 col-sm-8 control-content">
                                                {{ $order->orders_id }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-4 col-sm-4 control-label p-0">
                                                @lang('app.gift_code')
                                            </div>
                                            <div class="col-8 col-sm-8 control-content">
                                                {{ $order->code }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-4 col-sm-4 control-label p-0">
                                                @lang('app.gift_name')
                                            </div>
                                            <div class="col-8 col-sm-8 control-content">
                                                {{ $order->name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-4 col-sm-4 control-label p-0">
                                                @lang('app.category')
                                            </div>
                                            <div class="col-8 col-sm-8 control-content">
                                                {{ $order->group_name }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-4 col-sm-4 control-label p-0">
                                                @lang('app.category_code')
                                            </div>
                                            <div class="col-8 col-sm-8 control-content">
                                                {{ $order->group_code }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-md-offset-1 mod-scb-profile">
                                        <div class="form-group row">
                                            <div class="col-4 col-sm-4 control-label p-0">
                                                @lang('app.buyer')
                                            </div>
                                            <div class="col-8 col-sm-8 control-content">
                                                {{ $order->lastname." ".$order->firstname }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-4 col-sm-4 control-label p-0">
                                                @lang('app.date_of_purchase')
                                            </div>
                                            <div class="col-8 col-sm-8 control-content">
                                                {{ $order->created_at }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-4 col-sm-4 control-label p-0">
                                                @lang('app.amount')
                                            </div>
                                            <div class="col-8 col-sm-8 control-content">
                                                {{ $order->quantity }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-4 col-sm-4 control-label p-0">
                                                @lang('app.use_point')
                                            </div>
                                            <div class="col-8 col-sm-8 control-content">
                                                {{ $order->point }}
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-4 col-sm-4 control-label p-0">
                                                @lang('app.status')
                                            </div>
                                            <div class="col-8 col-sm-8 control-content">
                                                {{ $order->status }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <form action="{{ route('module.promotion.orders.buy.update_status',['id'=>$order->id]) }}" method="POST" class="form-horizontal form-ajax">
                                    <div class="form-group row">
                                        <div class="col-sm-12 control-label">
                                            <h4>{{ trans('backend.update_status') }}</h4>
                                        </div>
                                        <div class="col-md-12">
                                            <select name="status" id="status" class="form-control select2">
                                                <option value="" disabled selected>Chọn trạng thái</option>
                                                <option value="Đang sử dụng quà tặng">@lang('app.paid')</option>
                                                <option value="Quy đổi thành công">@lang('app.success')</option>
                                                <option value="Từ chối">@lang('app.reject')</option>
                                                <option value="Hủy">@lang('app.cancel')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row row-acts-btn">
                                        <div class="col-sm-12">
                                            <div class="btn-group act-btns">
                                                @can('promotion-purchase-history-edit')
                                                <button type="submit" class="btn btn-primary @if(\App\Permission::isUnitManager()) hidden @endif" data-must-checked="false"><i class="fa fa-save"></i> @lang('app.save')</button>
                                                @endcan
                                                <a href="{{ route('module.promotion') }}" class="btn btn-warning @if(\App\Permission::isUnitManager()) hidden @endif"><i class="fa fa-times-circle"></i> @lang('app.cancel')</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
