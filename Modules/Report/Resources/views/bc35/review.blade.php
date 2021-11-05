<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC35">
    <div class="row">
        <div class="col-md-3">

        </div>

        <div class="col-md-7">
            <div class="form-group row">
                <label class="form-label col-md-3">{{trans('backend.from_date')}}</label>
                <div class="col-md-6">
                    <input type="text" name="from_date" class="form-control datepicker">
                </div>
            </div>

            <div class="form-group row">
                <label class="form-label col-md-3">{{trans('backend.to_date')}}</label>
                <div class="col-md-6">
                    <input type="text" name="to_date" class="form-control datepicker">
                </div>
            </div>

            @for($i=2;$i<=5;$i++)
                <div class="form-group row">
                    <label class="form-label col-md-3">{{ trans('backend.unit_level', ['level' => $i]) }}</label>
                    <div class="col-md-6">
                        <select name="unit" class="form-control load-unit" id="unit-level-{{ $i }}" data-placeholder="{{ trans('backend.unit_level', ['level' => $i]) }}" data-level="{{ $i }}" data-loadchild="unit-level-{{ ($i+1) }}">

                        </select>
                    </div>
                </div>
            @endfor

            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button type="submit" id="btnSearch" class="btn btn-primary">{{ trans('backend.view_report') }}</button>

                    <button type="button" id="btnExport" class="btn btn-primary" name="btnExport">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> {{ trans('backend.export_excel') }}
                    </button>
                </div>
            </div>

        </div>
    </div>
</form>

<div id="chart_div"></div>
<br>
<div class="table-responsive">
    <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
        <thead>
            <tr class="tbl-heading">
                <th data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                <th data-field="code" data-align="left" data-width="10%">{{ trans('backend.employee_code') }}</th>
                <th data-field="fullname" data-align="left" data-width="15%">{{ trans('backend.fullname') }}</th>
                <th data-field="level2" data-align="left">Nhà hàng</th>
                <th data-field="level3" data-align="left">{{trans('backend.department')}}/Nhãn hàng</th>
                <th data-field="level4" data-align="left">Bộ phận/Tên cửa hàng</th>
                <th data-field="join_company" data-align="left">Ngày gia nhập</th>
                <th data-field="title_name" data-align="left">Vị trí</th>
                <th data-field="level" data-align="left">{{trans('backend.rank')}}</th>
                <th data-field="subject_name" data-align="left">Môn</th>
                <th data-field="date_complete" data-align="left">{{trans('backend.date_finish')}}</th>
                <th data-field="score" data-align="left">% {{trans("backend.finish")}}</th>
                <th data-field="teacher_code" data-align="left">{{ trans('backend.teacher') }}</th>
                <th data-field="teacher_name" data-align="left">{{ trans('backend.teacher_name') }}</th>
                <th data-field="course_child_name" data-align="left">Loại hoàn thành</th>
                <th data-field="status" data-align="left">{{trans('backend.status')}}</th>
                <th data-field="updated" data-align="left">{{trans('backend.user_updated_result')}}</th>
                <th data-field="created" data-align="left">{{trans('backend.created_at')}}</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        locale: '{{ \App::getLocale() }}',
        url: "{{ route('module.report.getData') }}",
    });

    function index_formatter(value, row, index) {
        return (index + 1);
    }

    $("#btnExport").on('click', function () {
        event.preventDefault();
        $("form[name=frm]").off("submit");
        $("form[name=frm]").submit();
        $("form[name=frm]").on("submit");
        return false;
    });
</script>
