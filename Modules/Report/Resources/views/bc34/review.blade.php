<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC34">
    <div class="row">
        <div class="col-md-3">

        </div>

        <div class="col-md-7">

            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.type') }}</label>
                </div>
                <div class="col-md-6">
                    <label class="radio-inline">
                        <input type="radio" name="optradio" value="1" checked> {{ trans('backend.day') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="optradio" value="2"> {{ trans('backend.month') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="optradio" value="3"> {{ trans('backend.year') }}
                    </label>
                </div>
            </div>

            <div class="form-group row select-1 ">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.day') }}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control datepicker" name="day">
                </div>
            </div>

            <div class="select-2 box-hidden">
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{ trans('backend.month') }}</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control datepicker-month" name="month">
                    </div>
                </div>
            </div>

            <div class="select-3 box-hidden">
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
                    <label>{{ trans('backend.seniority') }}</label>
                </div>
                <div class="col-md-6">
                    <div id="seniority-list">
                        <div class="row">
                            <div class="col">
                                <input type="text" name="seniority_from[]" class="form-control" placeholder="{{ trans('backend.from') }}">
                            </div>
                            <div class="col">
                                <input type="text" name="seniority_to[]" class="form-control" placeholder="{{ trans('backend.to') }}">
                            </div>
                        </div>
                    </div>

                    <a href="javascript:void(0)" id="add-seniority">{{ trans('backend.add_new') }}</a>
                </div>
            </div>

            @php
                $max_level = \App\Models\Categories\Unit::getMaxUnitLevel();
            @endphp

            @for($i=1;$i<=$max_level;$i++)
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{ trans('backend.unit_level', ['level' => $i]) }}</label>
                    </div>

                    <div class="col-md-6">
                        <select name="unit" id="unit-{{ $i }}" class="form-control load-unit" data-placeholder="{{ trans('backend.training_form') }}" data-level="{{ $i }}" data-loadchild="unit-{{ ($i+1) }}">
                        </select>
                    </div>
                </div>
            @endfor

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
                <th data-field="name" data-align="left">{{ trans('backend.age') }}</th>
                <th data-field="active" data-align="center">{{ trans('backend.unit_level', ['level' => 2]) }}</th>
                <th data-field="upcoming" data-align="center">{{ trans('backend.unit_level', ['level' => 3]) }}</th>
                <th data-field="finished" data-align="center">{{ trans('backend.seniority') }}</th>
            </tr>
        </thead>
    </table>
</div>

<template id="seniority-template">
    <div class="row mt-1">
        <div class="col">
            <input type="text" name="seniority_from[]" class="form-control" placeholder="{{ trans('backend.from') }}">
        </div>
        <div class="col">
            <input type="text" name="seniority_to[]" class="form-control" placeholder="{{ trans('backend.to') }}">
        </div>
    </div>
</template>

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

    $("#add-seniority").on('click', function () {
         let template = document.getElementById('seniority-template').innerHTML;
         $("#seniority-list").append(template);
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
