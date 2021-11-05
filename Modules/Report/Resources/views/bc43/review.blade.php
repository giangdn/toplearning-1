<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC43">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.form')}}</label>
                </div>
                <div class="col-md-6">
                    <select class="form-control" name="course_type">
                        <option value="">{{trans('backend.choose_form')}}</option>
                        <option value="1">{{trans('backend.online')}}</option>
                        <option value="2">{{trans('backend.offline')}}</option>
                    </select>
                </div>
            </div>
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
                    <label>{{ trans('backend.choose_course') }}</label>
                </div>
                <div class="col-md-6 course">
                    <select class="form-control load-course-bc43" data-placeholder="{{ trans('backend.choose_course') }}" name="course">
                        <option value="">{{ trans('backend.choose_course') }}</option>
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
                <th data-formatter="index_formatter" data-align="center">STT</th>
                <th data-field="course_name" data-align="center">{{ trans('backend.course_name') }}</th>
                <th data-field="start_date" data-align="left">{{trans('backend.start_date')}}</th>
                <th data-field="end_date" data-align="center">{{trans('backend.end_date')}}</th>
                <th data-field="training_unit" data-align="left">{{trans('backend.training_units')}}</th>
                <th data-field="code" data-align="left">MSNV</th>
                <th data-field="full_name" data-align="left">{{ trans('backend.fullname') }}</th>
                <th data-field="level_subject" data-align="left">{{ trans('backend.title') }}</th>
                <th data-field="" data-align="left">{{trans('backend.training_objectives')}}</th>
                <th data-field="reality_manager" data-align="left">{{trans('backend.apply_reality')}}</th>
                <th data-field="reason_reality_manager" data-align="left">{{trans('backend.not_apply_reality')}} ({{trans('backend.reason')}})</th>
                <th data-field="result" data-align="left">{{trans('backend.conclude')}}n</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

</script>
<script src="{{asset('styles/module/report/js/bc43.js')}}" type="text/javascript"></script>
