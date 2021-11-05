<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC42">
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
                <th data-field="time" data-align="center">NĐTC</th>
                <th data-field="in_plan" data-align="left">{{trans('backend.practical_organization')}} / {{trans('backend.plan')}} NĐTC</th>
                <th data-field="month" data-align="center">{{trans('backend.month')}}</th>
                <th data-field="progress" data-align="left">{{trans('backend.progress')}}</th>
                <th data-field="unit" data-align="left">{{ trans('backend.unit') }}</th>
                <th data-field="course_name" data-align="left">{{ trans('backend.course_name') }}</th>
                <th data-field="level_subject" data-align="left">{{trans('backend.levels')}}</th>
                <th data-field="training_form" data-align="left">{{trans('backend.training_form')}}</th>
                <th data-field="plan_name" data-align="left">{{trans('backend.plan')}}</th>
                <th data-field="object" data-align="left">{{ trans('backend.object') }}</th>
                <th data-field="training_unit" data-align="left">{{trans('backend.training_units')}}</th>
                <th data-field="training_location" data-align="left">Địa điểm đào tạo</th>
                <th data-field="start_date" data-align="center">{{trans('backend.start_date')}}</th>
                <th data-field="end_date" data-align="center">{{trans('backend.end_date')}}</th>
                <th data-field="num_class" data-align="center">{{trans('backend.number_class')}}</th>
                <th data-field="num_schedule" data-align="center">{{trans('backend.number_training')}}</th>
                <th data-field="num_student" data-align="center">{{trans('backend.number_trainees')}}</th>
                <th data-field="course_cost" data-align="center">{{trans('backend.cost')}} (Đồng)</th>
                <th data-field="note" data-align="left">{{ trans('backend.note') }}</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

</script>
<script src="{{asset('styles/module/report/js/bc42.js')}}" type="text/javascript"></script>
