<form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC40">
    <input type="hidden" name="created_by" value="{{ \Auth::id() }}">
    <div class="row">
        <div class="col-md-3">

        </div>

        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.from_date')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="from_date" class="form-control datepicker">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.to_date')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="to_date" class="form-control datepicker">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.choose_form')}}</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control" name="course_type" id="course_type">
                        <option value="">{{trans('backend.choose_form')}}</option>
                        <option value="1">Online</option>
                        <option value="2">In hourse</option>
                    </select>
                </div>
            </div>

            @for($i=2;$i<=3;$i++)
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="unit_id_{{ $i }}">{{ data_locale($level_name($i)->name, $level_name($i)->name_en) }} *</label>
                    </div>
                    <div class="col-md-6">
                        <select name="unit_id" id="unit_id_{{ $i }}" class="load-unit" 
                            data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. data_locale($level_name($i)->name, $level_name($i)->name_en) }} --" 
                            data-level="{{ $i }}" data-parent="{{ empty($unit[$i-1]->id) ? '' : $unit[$i-1]->id }}" 
                            data-loadchild="unit_id_{{ $i+1 }}"
                        >
                        </select>
                    </div>
                </div>
            @endfor

            @for($i=2;$i<=5;$i++)
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="area_id_{{ $i }}">{{ data_locale($level_name_area($i)->name, $level_name_area($i)->name_en) }}</label>
                    </div>
                    <div class="col-md-6">
                        <select name="area_id" id="area_id_{{ $i }}" class="load-area" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. data_locale($level_name_area($i)->name, $level_name_area($i)->name_en) }} --" data-level="{{ $i }}" data-parent="{{ empty($area[$i-1]->id) ? '' : $area[$i-1]->id }}" data-loadchild="area_id_{{ $i+1 }}">
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
            <th data-formatter="index_formatter" data-align="center" data-width="3%" rowspan="2">#</th>
            <th data-field="unit_2" rowspan="2">{{trans('backend.company')}}</th>
            <th data-field="unit_3" rowspan="2">{{trans('backend.distribution_chanel')}} ({{trans('backend.department')}})</th>
            <th data-field="area_2" rowspan="2">{{trans('backend.domain')}}</th>
            <th data-field="area_3" rowspan="2">{{trans('backend.area')}}</th>
            <th data-field="area_4" rowspan="2">{{trans('backend.region')}}</th>
            <th data-field="area_5" rowspan="2">{{trans('backend.office')}}</th>
            <th data-field="" colspan="2" data-align="center">Online</th>
            <th data-field="" colspan="2" data-align="center">In house</th>
        </tr>
        <tr class="tbl-heading">
            <th data-field="onl_regsiter" data-align="center">ĐK</th>
            <th data-field="onl_completed" data-align="center">HT</th>
            <th data-field="off_regsiter" data-align="center">ĐK</th>
            <th data-field="off_completed" data-align="center">HT</th>
        </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    var table = new BootstrapTable({
        url: "{{ route('module.report.getData') }}",
    });

    function index_formatter(value, row, index) {
        return (index + 1);
    }
    var form = $('#form-search');

    var coursetype = '';
    $('#course_type').on('change', function () {
        coursetype = $('#course_type option:selected').val();
    });

    function monthDiff(dt1, dt2) {
        var diff = (dt2.getTime() - dt1.getTime()) / 1000;
        diff /= (60 * 60 * 24 * 7 * 4);
        return Math.abs(Math.round(diff));
    }

    $('#btnSearch').on('click',function (e) {
        e.preventDefault();

        var from_date = $('input[name=from_date]').val().split("/").reverse().join("/");
        var to_date =  $('input[name=to_date]').val().split("/").reverse().join("/");

        if(monthDiff(new Date(from_date), new Date(to_date)) > 6){
            Swal.fire({
                title: 'Thông báo',
                width: '100%',
                position: 'center',
                html: 'Thời gian vượt quá 6 tháng. Dữ liệu sẽ chậm. Bạn muốn tiếp tục? <br> <div class="border-bottom pt-5" style="margin: -15px;"></div>',
                showCancelButton: true,
                confirmButtonText: "OK",
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.value) {
                    if(form.valid())
                        table.submit();

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
                            title: 'Biểu đồ tình hình học tập - ' + (coursetype == 1 ? 'Offline' : (coursetype == 2 ? 'Tập trung' : 'Cả 2')),
                            legend: {
                                position: 'bottom'
                            }
                        };

                        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

                        chart.draw(data, options);
                    }

                    return false;
                }else {
                    return false;
                }
            });
        }else {
            if(form.valid())
                table.submit();

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
                    title: 'Biểu đồ tình hình học tập - ' + (coursetype == 1 ? 'Offline' : (coursetype == 2 ? 'Tập trung' : 'Cả 2')),
                    legend: {
                        position: 'bottom'
                    }
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

                chart.draw(data, options);
            }
        }
    });

    form.validate({
        ignore: [],
        rules: {
            unit_id: { required: true },
        },
        messages: {
            unit_id: { required: "Chọn Đơn vị" },
        },
        errorPlacement: function (error, element) {
            var name = $(element).attr("name");
            error.appendTo($(element).parent());
        },
    });
    $('#btnExport').on('click', function (e) {
        e.preventDefault();
        if (form.valid())
            $(this).closest('form').submit();
        return false
    });
    
    // $('#btnExport').on('click',function (event) {
    //     event.preventDefault();
    //     $("form[name=frm]").off("submit");
    //     $("form[name=frm]").submit();
    //     $("form[name=frm]").on("submit");
    //     return false;
    // });
</script>
