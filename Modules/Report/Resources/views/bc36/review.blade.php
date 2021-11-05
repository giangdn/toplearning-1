<form name="frm2" id="form-search" action="{{ route('module.report.export') }}" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC36">

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <label class="col-md-3">{{ trans('backend.from_date') }}</label>
                <div class="col-md-6">
                    <input type="text" class="form-control datepicker" name="from_date">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3">{{ trans('backend.to_date') }}</label>
                <div class="col-md-6">
                    <input type="text" class="form-control datepicker" name="to_date">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3">{{ trans('backend.quiz_type') }}</label>
                <div class="col-md-6">
                    <select name="quiz_type[]" class="form-control load-quiz-type" data-placeholder="{{ trans('backend.quiz_type') }}" multiple></select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">{{ trans('backend.view_report') }}</button>

                    <button type="button" id="btnExport" class="btn btn-primary"><i class="fa fa-download"></i> {{ trans('backend.export_excel') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-4">
        <div id="chart_div"></div>
    </div>

    <div class="col-md-4">
        <div id="chart_div2"></div>
    </div>

    <div class="col-md-4">
        <div id="chart_div3"></div>
    </div>
</div>

<br>
<style>
    .tLight>thead>tr>th, .tDefault>thead>tr>th{
        padding: 10px 8px;
    }
    table>tbody>tr>th{
        font-weight: normal;
    }
</style>
<div class="table-responsive">
    <table class="tDefault table table-hover table-bordered bootstrap-table">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" data-formatter="index_formatter" rowspan="2">#</th>
                <th data-field="name" rowspan="2">Thể loại</th>
                <th colspan="2">Sắp diễn ra</th>
                <th colspan="2">Đang diễn ra</th>
                <th colspan="2">Đã kết thúc</th>
                <th data-field="rate" rowspan="2">Tỉ lệ (Đạt/không đạt)</th>
            </tr>
            <tr>
                <th data-field="total_quiz1">{{ trans('backend.exam') }}</th>
                <th data-field="total_register1">{{ trans('backend.examinee') }}</th>
                <th data-field="total_quiz2">{{ trans('backend.exam') }}</th>
                <th data-field="total_register2">{{ trans('backend.examinee') }}</th>
                <th data-field="total_quiz3">{{ trans('backend.exam') }}</th>
                <th data-field="total_register3">{{ trans('backend.examinee') }}</th>
            </tr>
        </thead>
    </table>
</div>
@section('footer')

    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: "{{ route('module.report.getData') }}",
        });

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        google.charts.load('current', {packages: ['bar']});

        $("#form-search").on('submit', function (event) {
            google.charts.setOnLoadCallback(drawBasic);
            google.charts.setOnLoadCallback(drawBasic2);
            google.charts.setOnLoadCallback(drawBasic3);
        });

        $('#btnExport').on('click',function (event) {
            event.preventDefault();
            $("form[name=frm2]").off("submit");
            $("form[name=frm2]").submit();
            $("form[name=frm2]").on("submit");
            return false;
        });

        function drawBasic() {
            var jsonData = $.ajax({
                type: "POST",
                url: "{{ route('module.report.data_chart') }}?chart=1",
                dataType: "json",
                async: false,
                data: $("#form-search").serialize()
            }).responseText;

            jsonData = JSON.parse(jsonData);
            var data = google.visualization.arrayToDataTable(jsonData);

            var options = {
                chart: {
                    title: 'Thống kê số lượng kỳ thi',
                    subtitle: '',
                },
                bars: 'horizontal'
            };

            var chart = new google.charts.Bar(document.getElementById('chart_div'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        function drawBasic2() {
            var jsonData = $.ajax({
                type: "POST",
                url: "{{ route('module.report.data_chart') }}?chart=2",
                dataType: "json",
                async: false,
                data: $("#form-search").serialize()
            }).responseText;

            jsonData = JSON.parse(jsonData);
            var data = google.visualization.arrayToDataTable(jsonData);

            var options = {
                chart: {
                    title: 'Thống kê lượt thi theo thể loại',
                    subtitle: '',
                },
                bars: 'horizontal'
            };

            var chart = new google.charts.Bar(document.getElementById('chart_div2'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }

        function drawBasic3() {
            var jsonData = $.ajax({
                type: "POST",
                url: "{{ route('module.report.data_chart') }}?chart=3",
                dataType: "json",
                async: false,
                data: $("#form-search").serialize()
            }).responseText;

            jsonData = JSON.parse(jsonData);
            var data = google.visualization.arrayToDataTable(jsonData);

            var options = {
                width: 600,
                height: 300,
                legend: { position: 'top', maxLines: 3 },
                bar: { groupWidth: '75%' },
                isStacked: true
            };

            var chart = new google.charts.Bar(document.getElementById('chart_div3'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>

@endsection
