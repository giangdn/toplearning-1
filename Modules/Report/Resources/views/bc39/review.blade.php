<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC39">
    <div class="form-group row">
        <div class="col-md-12 text-center">
            <button id="btnExport" class="btn btn-primary" name="btnExport">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export excel
            </button>
        </div>
    </div>
</form>

<div id="chart_div"></div>
<br>
<div class="table-responsive">
    <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
        <thead>
        <tr class="tbl-heading">
            <th data-align="center">Ngày</th>
            @for($i = 1; $i <= $day; $i++)
                <th data-align="center">{{ $i }}</th>
            @endfor
        </tr>
        <tbody>
            <tr class="tbl-heading">
                <td data-align="center">{{ trans('backend.quantity') }}</td>
                @for($i = 1; $i <= $day; $i++)
                    <td data-align="center">{{ \Modules\Report\Entities\BC39::countViewVideoInMonth($i) }}</td>
                @endfor
            </tr>
        </tbody>
        </thead>
    </table>
</div>

<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawBasic);
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
            title: '',
            legend: {
                position: 'top'
            }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

        chart.draw(data, options);
    }

    $('#btnExport').on('click',function (event) {
        event.preventDefault();
        $("form[name=frm]").off("submit");
        $("form[name=frm]").submit();
        $("form[name=frm]").on("submit");
        return false;
    });
</script>
