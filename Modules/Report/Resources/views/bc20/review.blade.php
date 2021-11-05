<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC20">
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
                <th data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                <th data-field="user_code" data-width="5%">MSNV</th>
                <th data-field="user_name">{{trans("backend.user")}}</th>
                <th data-field="title_name">{{ trans('backend.title') }}</th>
                <th data-field="unit_name">{{ trans('backend.unit') }}</th>
                <th data-field="subject_code">Mã học phần</th>
                <th data-field="subject_name">Tên học phần</th>
                <th data-field="capabilities_name">Năng lực cần bồi dưỡng</th>
                <th data-field="priority_level">Mức độ ưu tiên</th>
                <th data-field="training_time">Thời gian đào tạo</th>
                <th data-field="training_form">{{trans('backend.training_program_form')}}</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

</script>
<script src="{{asset('styles/module/report/js/bc20.js')}}" type="text/javascript"></script>
