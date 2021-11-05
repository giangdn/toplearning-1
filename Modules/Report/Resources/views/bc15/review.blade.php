<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC15">
    <div class="row">
        <div class="col-md-3">

        </div>
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
                    <label>{{trans("backend.choose_unit")}}</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control load-unit" name="unit_id" data-placeholder="{{trans('backend.choose_unit')}}">

                    </select>
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-formatter="index_formatter" data-align="center">#</th>
                <th data-field="code">MSNV</th>
                <th data-field="full_name">{{trans('backend.student')}}</th>
                <th data-field="unit_name">{{ trans('backend.unit') }}</th>
                <th data-field="title_name">{{ trans('backend.title') }}</th>
                <th data-field="course_code">{{ trans('backend.course_code') }}</th>
                <th data-field="course_name">{{ trans('backend.course') }}</th>
                <th data-field="course_type">{{trans('backend.form')}}</th>
                <th data-field="training_unit">{{trans('backend.training_units')}}</th>
                <th data-field="training_form">{{trans('backend.training_program_form')}}</th>
                <th data-field="start_date" data-align="center">{{trans("backend.time_start")}}</th>
                <th data-field="end_date" data-align="center">{{trans("backend.end_time")}}</th>
                <th data-field="course_cost" data-align="center">{{trans('backend.cost')}} (VND)</th>
                <th data-field="score_scorm" data-align="center" data-width="5%">{{trans('backend.lesson_score')}}</th>
                <th data-field="score" data-align="center" data-width="5%">{{ trans('backend.test_score') }}</th>
                <th data-field="result_achieved" data-align="center" data-width="5%">{{trans("backend.achieved")}}</th>
                <th data-field="result_not_achieved" data-align="center" data-width="5%">{{trans("backend.not_achieved")}}</th>
                <th data-field="indem" data-align="center" data-width="5%">{{trans('backend.commitment_training')}} (Số)</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

</script>
<script src="{{asset('styles/module/report/js/bc15.js')}}" type="text/javascript"></script>
