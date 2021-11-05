<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC19">
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
                    <label>{{trans("backend.user")}}</label>
                </div>
                <div class="col-md-6">
                    <select type="text" name="user_id" class="form-control load-user" data-placeholder="{{trans(backend.choose_user)}}">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.title') }}</label>
                </div>
                <div class="col-md-6">
                    <select type="text" name="title_id" class="form-control load-title" data-placeholder="{{trans('backend.choose_title')}}">
                        <option value=""></option>
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
                <th rowspan="2" data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                <th rowspan="2" data-field="user_code">MSNV</th>
                <th rowspan="2" data-field="user_name">{{trans("backend.user")}}</th>
                <th rowspan="2" data-field="title_name">{{ trans('backend.title') }}</th>
                <th rowspan="2" data-field="unit_name">{{ trans('backend.unit') }}</th>
                <th rowspan="2" data-field="percent" data-width="5%">{{trans('backend.ratio')}} (%)</th>
                <th rowspan="2" data-field="group_percent" data-align="center" data-width="5%">{{trans("backend.group")}}</th>
                @foreach ($capa_cate as $cate)
                    @php
                        $capabilites = $capa($cate->id);
                    @endphp
                    <th colspan="{{$capabilites->count()}}">{{$cate->name}}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($capa_cate as $cate)
                    @php
                        $capabilites = $capa($cate->id);
                    @endphp

                    @foreach ($capabilites as $capability)
                        <th data-field="capa_{{$capability->id}}" data-align="center">{{$capability->name}}</th>
                    @endforeach
                @endforeach
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

</script>
<script src="{{asset('styles/module/report/js/bc19.js')}}" type="text/javascript"></script>
