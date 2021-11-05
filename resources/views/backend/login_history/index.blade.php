{{-- @extends('layouts.backend')

@section('page_title', 'Lịch sử truy cập')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Lịch sử truy cập</span>
        </h2>
    </div>
@endsection --}}

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/report/css/list.css') }}">
    <script src="{{ asset('js/charts-loader.js') }}" type="text/javascript"></script>
    <script src="{{asset('styles/module/report/js/report.js')}}" type="text/javascript"></script>

    <style>
        .table > thead > tr > .th-second{
            top: 40px;
        }

        table video {
            width: 50%;
            height: auto;
        }

        table img {
            width: 50% !important;
            height: auto !important;
        }
    </style>
@endsection

{{-- @section('content') --}}
<div role="main" id="report" class="pt-2">
    <form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
        @csrf
        <input type="hidden" name="report" value="BC18">
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-7">
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>Đơn vị</label>
                    </div>
                    <div class="col-md-6">
                        <select name="unit_id" class="form-control load-unit" data-placeholder="-- {{ trans('backend.unit') }} --"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>Khu vực</label>
                    </div>
                    <div class="col-md-6">
                        <select name="area" id="area" class="form-control load-area" data-placeholder="-- Khu vực --"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>Mã nhân viên</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="userCode" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>Tên nhân viên</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="userName" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{trans('backend.date_from')}} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="from_date" class="form-control datepicker">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{trans('backend.date_to')}} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="to_date" class="form-control datepicker">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <button type="submit" id="btnSearch" class="btn btn-primary">Xem lịch sử truy cập</button>
                        <button id="btnExport" class="btn btn-primary" name="btnExport">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export excel
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
    <br>
    <div class="table-responsive">
        <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report.getData')}}">
            <thead>
                <tr class="tbl-heading">
                    <th data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                    <th data-field="user_code">MSNV</th>
                    <th data-field="user_name">{{trans('backend.student')}}</th>
                    <th data-field="number_hits" data-align="center" data-width="5%">{{trans("backend.access_number")}}</th>
                    <th data-field="start_date" data-align="center" data-width="15%">{{trans("backend.time_start")}}</th>
                    <th data-field="end_date" data-align="center" data-width="15%">{{trans("backend.last_access")}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }

    </script>
    <script src="{{asset('styles/module/report/js/bc18.js')}}" type="text/javascript"></script>
{{-- @endsection --}}
