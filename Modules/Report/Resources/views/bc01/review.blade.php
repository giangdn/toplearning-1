<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC01">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_from')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="from_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_to')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="to_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans("backend.user")}}</label>
                </div>
                <div class="col-md-6">
                    <select name="user_id" id="" class="form-control select2 load-user" data-placeholder="{{trans('backend.choose_user')}}"></select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button type="submit" id="btnSearch" class="btn btn-primary">{{trans('backend.view_report')}}</button>
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
    <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report.getData')}}">
        <thead>
        <tr class="tbl-heading">
            <th data-formatter="index_formatter" data-align="center">#</th>
            <th data-field="code" data-align="center">{{trans('backend.employee_code')}}</th>
            <th data-field="fullname" data-align="left">{{ trans('backend.fullname') }}</th>
            <th data-field="title_name" data-align="left">{{ trans('backend.title') }}</th>
            <th data-field="unit_name" data-align="left">{{trans('backend.direct_units')}}</th>
            <th data-field="parent_unit_name" data-align="left">{{trans('backend.indirect_units_level')}} 1</th>
            <th data-field="unit_name_level2" data-align="left">{{trans('backend.company')}}</th>
            <th data-field="course_code" data-align="left">{{ trans('backend.course_code') }}</th>
            <th data-field="course_name" data-align="left">{{ trans('backend.course_name') }}</th>
            <th data-field="start_date" data-align="left">{{trans('backend.start_date')}}</th>
            <th data-field="end_date" data-align="left">{{trans('backend.end_date')}}</th>
            <th data-field="cost_commit" data-align="left">{{ trans('backend.commitment_amount') }}</th>
            <th data-field="cost_indemnify" data-align="left">{{trans('backend.refund_amount')}}</th>
            <th data-field="day_commit" data-align="center">{{trans('backend.start_date_commitment')}}</th>
            <th data-field="month_commit" data-align="center">{{trans('backend.coimmitted_months')}}</th>
            <th data-field="date_diff" data-align="center">{{trans('backend.number_month_remain')}}</th>
            <th data-field="contract" data-align="center">{{trans('backend.number_committed_contrac')}}</th>
            <th data-field="day_off" data-align="center">{{trans('backend.date_off')}}</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
</script>
<script src="{{asset('styles/module/report/js/bc1.js')}}" type="text/javascript"></script>
