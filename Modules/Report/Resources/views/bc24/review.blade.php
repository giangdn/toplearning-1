<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC24">
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
                    <label>{{trans("backend.choose_unit")}}</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control load-unit seclect2" name="unit_id" data-placeholder="{{trans('backend.choose_unit')}}"></select>
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
                <th data-field="unit_name">{{ trans('backend.unit') }}</th>
                <th data-field="subject_name">{{trans('backend.thematic')}}</th>
                <th data-field="intend">{{trans('backend.month')}}</th>
                <th data-field="content">{{trans('backend.topical_content')}}</th>
                <th data-field="purpose">{{trans('backend.training_objectives')}}</th>
                <th data-field="duration">{{trans('backend.timer')}} ({{trans('backend.session')}})</th>
                <th data-field="title">{{ trans('backend.object') }}</th>
                <th data-field="amount">{{ trans('backend.quantity') }}</th>
                <th data-field="teacher">{{ trans('backend.teacher') }}</th>
                <th data-field="attach">{{trans('backend.document')}}</th>
                <th data-field="note">{{trans('backend.request_support')}} ({{trans('backend.if')}})</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

</script>
<script src="{{asset('styles/module/report/js/bc24.js')}}" type="text/javascript"></script>
