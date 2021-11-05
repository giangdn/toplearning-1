<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC41">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.choose_title')}}</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control load-title" name="title_id" data-placeholder="{{trans('backend.choose_title')}}">
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
                <th data-formatter="index_formatter" data-align="center" data-width="1%">STT</th>
                <th data-field="user_code" data-align="left">{{trans('backend.employee_code')}}</th>
                <th data-field="user_name" data-align="left">{{trans('backend.fullname')}}</th>
                <th data-field="title_name" data-align="left">{{ trans('backend.title') }}</th>
                <th data-field="unit_name" data-align="left">{{trans('backend.direct_units')}}</th>
                <th data-field="parent_unit_name" data-align="left">{{trans('backend.indirect_units_level')}} 1</th>
                <th data-field="unit_name_level2" data-align="left">{{trans('backend.company')}}</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

</script>
<script src="{{asset('styles/module/report/js/bc41.js')}}" type="text/javascript"></script>
