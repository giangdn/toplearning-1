<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC06">
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
                    <label>{{trans('backend.choose_form')}}</label>
                </div>
                <div class="col-md-6 course">
                    <select class="form-control" data-placeholder="{{trans('backend.choose_form')}}" name="teacher_type">
                        <option value="">{{trans('backend.choose_form')}}</option>
                        <option value="1">{{trans("backend.internal")}}</option>
                        <option value="2">{{trans("backend.examinee_outside")}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.choose_teacher') }}</label>
                </div>
                <div class="col-md-6 course">
                    <select class="form-control" data-placeholder="{{ trans('backend.choose_teacher') }}" name="teacher">
                        <option value="">{{ trans('backend.choose_teacher') }}</option>
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
            <th data-field="code" data-align="center">{{trans('backend.employee_code')}}</th>
            <th data-field="teacher">{{ trans('backend.fullname') }} GV</th>
            <th data-field="course_code" data-align="center" data-width="70px">{{ trans('backend.course_code') }}</th>
            <th data-field="course_name" data-align="left">{{ trans('backend.course') }}</th>
            <th data-field="start_date" data-width="250px" data-align="center">{{trans('backend.start_date')}}</th>
            <th data-field="end_date" >{{trans('backend.end_date')}}</th>
            <th data-field="lesson" data-align="center">{{trans('backend.number_hours_class')}}</th>
            <th data-field="num_lesson_date" data-align="center">{{trans('backend.number_date_class')}}</th>
            <th data-field="training_location" data-align="center">{{trans('backend.locations')}}</th>
            <th data-field="cost" data-align="right">{{trans('backend.cost')}}</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
</script>
<script src="{{asset('styles/module/report/js/bc06.js')}}" type="text/javascript"></script>

