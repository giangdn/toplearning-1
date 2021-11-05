@extends('layouts.backend')

@section('page_title', 'Đề xuất kế hoạch đào tạo tháng')
@section('header')
    <link rel="stylesheet" href="{{ asset('styles/vendor/sweetalert2/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/module/user/css/user.css') }}">
    <script src="{{asset('styles/vendor/bootstrap/js/bs-custom-file-input.min.js')}}"></script>
    <script src="{{ asset('styles/module/plansuggest/js/plan_suggest.js') }}"></script>
@endsection
@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div role="main">
        <div class="row">
            <div class="col-md-7 form-inline">
                <form class="form-inline form-search mb-3" id="form-search">
                    <div class="w-25 pl-0 pr-1">
                        <select class="form-control w-100" name="month">
                            <option value="">{{trans('backend.filter_month')}}</option>
                            @for ($i = 1; $i <=12; $i++)
                                <option value="{{$i}}">Tháng {{$i}}</option>
                            @endfor
                        </select>

                    </div>
                    <div class="w-25 pl-0 pr-1">
                        <select class="form-control w-100" allowClear="false" name="year">
                            <option value="">{{trans('backend.filter_year')}}</option>
                            @for ($i = 2019; $i <= date('Y'); $i++)
                                <option value="{{$i}}">Năm {{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class=" pl-0 pr-1 w-25">
                        <select class="form-control select2" name="unit">
                            <option value="">{{trans('backend.filter_unit')}}</option>
                            @foreach($unit as $item)
                                <option value="{{$item->code}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class=" pl-0 pr-1 w-25">
                        <select class="form-control w-100" name="status">
                            <option value="">{{trans('backend.filter_status')}}</option>
                            <option value="1">{{trans('backend.pending')}}</option>
                            <option value="2">{{trans('backend.approved')}}</option>
                            <option value="3">{{trans('backend.deny')}}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;{{trans('backend.search')}}</button>
                </form>
            </div>
            <div class="col-md-5 text-right act-btns">
                <div class="pull-right">
                    <div class="btn-group">
                        @if (userCan('plan-suggest-approve'))
                            <button id="btnApproved" data-url="{{route('module.plan_suggest.approved')}}" class="btn btn-primary" name="approved"><i class="fa fa-check"></i> {{trans('backend.approve')}}</button>
                            <button id="btnDeny" data-url="{{route('module.plan_suggest.deny')}}" class="btn btn-danger" name="deny" ><i class="fa fa-times"></i> {{trans('backend.deny')}}</button>
                        @endif
                        @if(\App\Permission::isUnitManager() || userCan('plan-suggest-create'))
                            <a href="{{ route('module.plan_suggest.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> {{trans('backend.create')}}</a>
                        @endif
                        @if(\App\Permission::isUnitManager() || userCan('plan-suggest-delete'))
                            <button class="btn btn-danger" id="btnDelete" value="3" data-url="{{route('module.plan_suggest.remove')}}"><i class="fa fa-trash"></i> {{trans('backend.delete')}}</button>
                        @endif

                        @if(\App\Permission::isUnitManager() || userCan('plan-suggest-export'))
                        <a class="btn btn-info" href="javascript:void(0)" id="export-excel">
                            <i class="fa fa-download"></i> Export
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
                <thead>
                <tr class="tbl-heading">
                    <th data-checkbox="true"></th>
                    @if(\App\Permission::isAdmin())
                    <th data-field="unit_name" data-align="left">{{trans('backend.unit')}}</th>
                    @endif
                    <th data-field="subject_name" data-formatter="name_formatter">{{trans('backend.training_content')}}</th>
                    <th data-field="amount" data-align="center">{{trans('backend.quantity')}}</th>
                    <th data-field="type" data-align="left">{{trans('backend.form')}}</th>
                    <th data-field="training_form" data-align="left">{{trans('backend.type')}}</th>
                    <th data-field="time" data-align="center">{{trans('backend.time')}}</th>
                    <th data-field="attach" data-align="center" data-formatter="attach_formatter">{{trans('backend.attach_file')}}</th>
                    <th data-field="attach_report" data-align="center" data-formatter="attach_report_formatter">{{trans('backend.report_file')}}</th>
                    <th data-align="center" data-formatter="status_formatter" >{{trans('backend.status')}}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        function name_formatter(value, row, index) {
            return '<a href="'+row.edit_url+'">' +row.subject_name+ '</a>';
        }
        function status_formatter(value, row, index) {
            if (row.status==1)
                return '<span class="text-warning">{{trans("backend.pending")}}</span>';
            else if (row.status==2)
                return '<span class="text-success">{{trans("backend.approved")}}</span>';
            else if (row.status==3)
                return '<span class="text-danger">{{trans("backend.deny")}}</span>';
            else
                return '{{trans("backend.unsent")}}';
        }
        function attach_formatter(value,row,index) {
            if (row.download_file){
                return '<a href="'+row.download_file+'"><i class="fa fa-file" aria-hidden="true"></i></a>'
            }
        }
        function attach_report_formatter(value,row,index) {
            if (row.download_report){
                return '<a href="'+row.download_report+'"><i class="fa fa-file" aria-hidden="true"></i></a>'
            }
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.plan_suggest.getData') }}',
            remove_url: '{{ route('module.plan_suggest.remove') }}'
        });

        $("#export-excel").on('click', function () {
            let form_search = $("#form-search").serialize();
            window.location = '{{ route('module.plan_suggest.export') }}?'+form_search;
        });

    </script>

@endsection
