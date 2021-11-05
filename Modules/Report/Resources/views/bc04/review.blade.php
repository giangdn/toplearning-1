<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    {{--@foreach ($param as $index => $item)--}}
        {{--<input type="hidden" name="{{$item['name']}}" value="{{$item['value']}}" />--}}
    {{--@endforeach--}}
    <input type="hidden" name="report" value="BC04">
    <div class="row">
        <div class="col-md-2">

        </div>
        <div class="col-md-8">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_from')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="fromDate" id="fromDate" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_to')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="toDate" class="form-control datepicker-date" >
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.training_program_form')}}</label>
                </div>
                <div class="col-md-6">
                    <select name="training_from" id="training_from" class="form-control">
                        <option value="">{{trans('backend.choose_training_program_form')}}</option>
                        @foreach ($training_from as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" id="btnSearch" class="btn btn-primary">{{trans('backend.view_report')}}</button>
                <button id="btnExport" class="btn btn-primary" name="btnExport">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export excel
                </button>
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
            <th rowspan="2" data-field="course_name" data-width="150px" data-align="left">{{ trans('backend.course') }}</th>
            <th rowspan="2" data-field="course_type" >{{trans('backend.form')}}</th>
            <th rowspan="2" data-field="course_time">{{trans('backend.timer')}}</th>
            <th rowspan="2" data-field="start_date" >{{trans('backend.date_from')}}</th>
            <th rowspan="2" data-field="end_date" >{{trans('backend.date_to')}}</th>
            <th rowspan="2" data-field="training_unit" >{{trans('backend.training_units')}}</th>
            <th rowspan="2" data-field="cost_class" data-align="center">{{trans('backend.cost')}}</th>
            <th rowspan="2" data-field="teacher" >{{ trans('backend.teacher') }}</th>
            <th rowspan="2" data-field="students" data-align="center" data-width="80px">{{trans("backend.total_learners")}}</th>
            <th colspan="2"  data-align="center" data-width="70px">{{ trans('backend.join') }}</th>
            <th colspan="2"  data-align="left">{{ trans('backend.not_join') }}</th>
            <th colspan="2"  data-align="left">{{trans("backend.finish")}}</th>
            <th colspan="2"  data-align="left">{{trans("backend.not_completed")}}</th>
            <th rowspan="2" data-field="object" >{{trans("backend.object_join")}}</th>
        </tr>
        <tr>
            <th data-field="attended">SL</th>
            <th data-field="percent_attended">%</th>
            <th data-field="unattended">SL</th>
            <th data-field="percent_unattended">%</th>
            <th data-field="complete">SL</th>
            <th data-field="percent_complete">%</th>
            <th data-field="uncomplete">SL</th>
            <th data-field="percent_uncomplete">%</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    };
</script>
<script src="{{asset('styles/module/report/js/bc04.js')}}" type="text/javascript"></script>