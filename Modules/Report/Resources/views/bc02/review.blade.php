<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC02">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-2 control-label">
                    <label>{{trans('backend.month')}}</label>
                </div>
                <div class="col-md-4">
                    <input type="text" name="date" id="date" class="form-control datepicker-month">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-2"></div>
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report.getData')}}">
        <thead>
        <tr class="tbl-heading">
            <th data-formatter="index_formatter" data-align="center">#</th>
            <th data-field="code" data-align="center">{{trans('backend.employee_code')}}</th>
            <th data-field="fullname">{{ trans('backend.fullname') }}</th>
            <th data-field="title_name" data-align="center">{{ trans('backend.title') }}</th>
            <th data-field="unit_name" data-align="left">{{trans('backend.direct_units')}}</th>
            <th data-field="parent_unit_name" data-align="left">{{trans('backend.indirect_units_level')}} 1</th>
            <th data-field="unit_name_level2" data-align="left">{{trans('backend.company')}}</th>
            <th data-field="course_code" data-align="left">{{ trans('backend.course_code') }}</th>
            <th data-field="course_name" data-align="left">{{ trans('backend.course_name') }}</th>
            <th data-field="course_type" data-align="center">{{trans('backend.training_program_form')}}</th>
            <th data-field="course_time">{{trans('backend.training_time')}}</th>
            <th data-field="start_date">{{trans('backend.start_date')}}</th>
            <th data-field="end_date">{{trans('backend.end_date')}}</th>
            <th data-field="training_form">{{trans('backend.training_form')}}</th>
            <th data-field="training_unit">{{trans('backend.training_units')}}</th>
            <th data-field="teacher">{{ trans('backend.teacher') }}</th>
            <th data-field="commit_date">{{trans('backend.start_date_commitment')}}</th>
            <th data-field="commit_month">{{trans('backend.commitment_time')}} ({{trans('backend.month')}})</th>
            <th data-field="start_date">{{trans('backend.date_join')}}</th>
            <th data-field="end_date">{{trans('backend.date_finish')}}</th>
            <th data-field="score_scorm">{{trans('backend.lesson_score')}}</th>
            <th data-field="score">{{ trans('backend.test_score') }}</th>
            <th data-field="score_final">{{trans('backend.final_grade')}}</th>
            <th data-field="result_final">{{ trans('backend.result') }}</th>
            <th data-field="note" >{{ trans('backend.note') }}</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
</script>
<script src="{{asset('styles/module/report/js/bc02.js')}}" type="text/javascript"></script>
