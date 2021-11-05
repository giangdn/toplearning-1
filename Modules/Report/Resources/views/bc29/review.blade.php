<form name="frm2" id="form-search" action="{{ route('module.report.review', ['id' => 'BC29']) }}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC29">

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.type') }}</label>
                </div>
                <div class="col-md-6">
                    <label class="radio-inline">
                        <input type="radio" name="optradio" value="1" checked> {{ trans('backend.month') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="optradio" value="2"> {{ trans('backend.year') }}
                    </label>
                </div>
            </div>

            <div class="select-1">
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{ trans('backend.month') }}</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control datepicker-month" name="month">
                    </div>
                </div>
            </div>

            <div class="select-2 box-hidden">
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{ trans('backend.year') }}</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control datepicker-year" name="year">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.age') }}</label>
                </div>
                <div class="col-md-6">
                    <div id="age-list">
                        <div class="row">
                            <div class="col">
                                <input type="text" name="age_from[]" class="form-control" placeholder="{{ trans('backend.from') }}">
                            </div>
                            <div class="col">
                                <input type="text" name="age_to[]" class="form-control" placeholder="{{ trans('backend.to') }}">
                            </div>
                        </div>
                    </div>

                    <a href="javascript:void(0)" id="add-age">{{ trans('backend.add_new') }}</a>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.unit') }}</label>
                </div>
                <div class="col-md-6">
                    <select name="units[]" class="form-control load-unit" data-placeholder="Chọn đơn vị" multiple></select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">{{ trans('backend.view_report') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
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
                <th data-align="center" data-formatter="index_formatter">#</th>
                <th data-field="age">{{ trans('backend.age') }}</th>
                <th data-field="level2">{{ trans('backend.unit_level', ['level' => 2]) }}</th>
                <th data-field="level3">{{ trans('backend.unit_level', ['level' => 3]) }}</th>
                <th data-field="total_access">{{ trans('backend.total_access_number') }}</th>
            </tr>
        </thead>
    </table>
</div>
@section('footer')
    <template id="age-template">
        <div class="row mt-1">
            <div class="col">
                <input type="text" name="age_from[]" class="form-control" placeholder="{{ trans('backend.from') }}">
            </div>
            <div class="col">
                <input type="text" name="age_to[]" class="form-control" placeholder="{{ trans('backend.to') }}">
            </div>
        </div>
    </template>

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

        $("#add-age").on('click', function () {
            let template = document.getElementById('age-template').innerHTML;
            $("#age-list").append(template);
        });

        $('#btnExport').on('click',function (event) {
            event.preventDefault();
            $("form[name=frm]").off("submit");
            $("form[name=frm]").submit();
            $("form[name=frm]").on("submit");
            return false;
        });

        $("input[name=optradio]").on('change', function () {
            let iv = parseInt($(this).val());
            for (let i=1;i<=3;i++) {
                if (i == iv) {
                    $(".select-" + i).show('slow');
                }
                else {
                    $(".select-" + i).hide('slow');
                }
            }
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
                title: 'Company Performance',
                hAxis: {title: 'Tháng',  titleTextStyle: {color: '#333'}},
                vAxis: {minValue: 0}
            };

            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));

            chart.draw(data, options);
        }
    </script>
    <script src="{{ asset('styles/module/report/js/bc29.js') }}" type="text/javascript"></script>
@endsection
