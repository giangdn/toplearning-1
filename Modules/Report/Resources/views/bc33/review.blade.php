<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC33">
    <div class="row">
        <div class="col-md-3">

        </div>

        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.from_date') }}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="from_date" class="form-control datepicker-date">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.to_date') }}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="to_date" class="form-control datepicker-date">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.type') }}</label>
                </div>
                <div class="col-md-6">
                    <select name="type" class="form-control">
                        <option value="1">{{ trans('backend.online') }}</option>
                        <option value="2">{{ trans('backend.offline') }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.training_form') }}</label>
                </div>
                <div class="col-md-6">
                    <select name="training_form[]" class="form-control load-training-form" data-placeholder="{{ trans('backend.training_form') }}" multiple>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button type="submit" id="btnSearch" class="btn btn-primary">{{ trans('backend.view_report') }}</button>

                    <button id="btnExport" class="btn btn-primary" name="btnExport">
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
                <th data-field="name" data-align="left">{{ trans('backend.form') }}</th>
                <th data-field="active" data-align="center">Đang diễn ra</th>`
                <th data-field="upcoming" data-align="center">Sắp diễn ra</th>
                <th data-field="finished" data-align="center">Đã kết thúc</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    var table = new LoadBootstrapTable({
        locale: '{{ \App::getLocale() }}',
        url: "{{ route('module.report.getData') }}",
    });

    function index_formatter(value, row, index) {
        return (index + 1);
    }

    google.charts.load('current', {packages: ['corechart', 'bar']});

    $("#form-search").on('submit', function (event) {
        google.charts.setOnLoadCallback(drawBasic);
    });

    $('#btnExport').on('click',function (event) {
        event.preventDefault();
        $("form[name=frm]").off("submit");
        $("form[name=frm]").submit();
        $("form[name=frm]").on("submit");
        return false;
    });

    function drawBasic() {
        var jsonData = $.ajax({
            type: "POST",
            url: "{{ route('module.report.data_chart') }}",
            dataType: "json",
            async: false,
            data: $("#form-search").serialize()
        }).responseText;

        jsonData = JSON.parse(jsonData);
        var data = google.visualization.arrayToDataTable(jsonData);

        var options = {
            title: 'Số lượng khóa học theo hình thức',
            chartArea: {width: '50%', height: '600px'},
            hAxis: {
                title: 'Tổng khóa học',
                minValue: 0
            },
            vAxis: {
                title: '{{ trans('backend.from') }}'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

        chart.draw(data, options);
    }
</script>
