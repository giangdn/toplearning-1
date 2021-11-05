<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC11">
    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.choose_form')}}</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control" name="type">
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
                    <input type="text" name="from_date" class="form-control datepicker-date" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_to')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="to_date" class="form-control datepicker-date" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.choose_course') }}</label>
                </div>
                <div class="col-md-6 course">
                    <select class="form-control load-course-bc09" data-placeholder="{{ trans('backend.choose_course') }}" name="course">
                        {{--<option value="">{{ trans('backend.choose_course') }}</option>--}}
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
                <th data-formatter="index_formatter" data-align="center" data-width="5%">#</th>
                <th data-field="name">{{ trans('backend.content') }}</th>
                <th data-field="answers"></th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
</script>
<script src="{{asset('styles/module/report/js/bc09.js')}}" type="text/javascript"></script>
