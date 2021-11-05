<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC26">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.year')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="year" class="form-control datepicker-year">
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
                <th rowspan="2" data-formatter="index_formatter" data-align="center">#</th>
                <th rowspan="2" data-field="unit_name">{{ trans('backend.unit') }}</th>
                <th rowspan="2" data-field="total" data-align="center">{{trans('backend.total')}}</th>
                <th colspan="4">{{trans('backend.number_training_topic')}}</th>
                <th rowspan="2" data-field="subject_name">{{trans('backend.thematic_training_group')}}</th>
            </tr>
            <tr class="tbl-heading">
                <th data-field="quarter_1" data-align="center">{{trans('backend.precious')}} 1</th>
                <th data-field="quarter_2" data-align="center">{{trans('backend.precious')}} 2</th>
                <th data-field="quarter_3" data-align="center">{{trans('backend.precious')}} 3</th>
                <th data-field="quarter_4" data-align="center">{{trans('backend.precious')}} 4</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
</script>
<script src="{{asset('styles/module/report/js/bc26.js')}}" type="text/javascript"></script>
