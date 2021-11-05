<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC13">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="month">Tháng</label>
                </div>
                <div class="col-md-9">
                    <input name="month" id="month" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="year">Năm</label>
                </div>
                <div class="col-md-9">
                    <input name="year" id="year" type="text" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3 control-label">
                    <label for="area_id">Khu vực</label>
                </div>
                <div class="col-md-9">
                    <select id="area_id" class="load-area" data-placeholder="Khu vực" data-level="3" multiple></select>
                    <input type="hidden" name="area_id" value="">
                </div>
            </div>
            @for($i = 1; $i <= 5; $i++)
                <div class="form-group row">
                    <div class="col-sm-3 control-label">
                        <label for="unit_id_{{ $i }}">{{ data_locale($level_name($i)->name, $level_name($i)->name_en) }}</label>
                    </div>
                    <div class="col-md-9">
                        <select name="unit_id" id="unit_id_{{ $i }}" class="load-unit" data-placeholder="-- {{ data_locale('Chọn', 'Choose') .' '. data_locale($level_name($i)->name, $level_name($i)->name_en) }} --" data-level="{{ $i }}" data-parent="{{ empty($unit[$i-1]->id) ? '' : $unit[$i-1]->id }}" data-loadchild="unit_id_{{ $i+1 }}">
                        </select>
                    </div>
                </div>
            @endfor
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" id="btnSearch" class="btn btn-primary">{{trans('backend.view_report')}}</button>
            <button id="btnExport" class="btn btn-primary" name="btnExport">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export excel
            </button>
        </div>
    </div>
</form>
<br>
<div class="table-responsive">
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th data-align="center" data-formatter="index_formatter">STT</th>
                <th data-field="area_name">Khu vực</th>
                <th data-field="unit_name_1">Chi nhánh/ Phòng Hội sở</th>
                <th data-field="unit_name_2">Phòng GD/ Phòng nghiệp vụ tại Chi nhánh</th>
                <th data-field="unit_type">Loại ĐV</th>
                <th data-field="avg_user_by_year">Tổng nhân sự BQ trong năm</th>
                <th data-field="actual_number_participants">Số người tham gia thực tế</th>
                <th data-field="hits_actual_participation">Số lượt tham gia thực tế</th>
                @foreach($traing_cost as $cost)
                    <th data-field="traing_cost{{ $cost->id }}"> {{ $cost->name }}</th>
                @endforeach
                @foreach($student_cost as $student)
                    <th data-field="student_cost{{ $student->id }}"> {{ $student->name }}</th>
                @endforeach
                <th data-field="total_cost">Tổng CP</th>
                <th data-field="avg_cost_user">Chi phí BQ/ Nhân sự</th>
                <th data-field="avg_cost_actual_number_participants">Chi phí BQ/ Người tham gia thực tế</th>
                <th data-field="avg_cost_hits_actual_participation">Chi phí BQ/ Lượt</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    $(document).ready(function () {
        var table = new BootstrapTable({
            url: $('#bootstraptable').data('url'),
        });
        var form = $('#form-search');
        form.validate({
            ignore: [],
            rules : {
                month: {required : true},
                area_id: {required : true},
            },
            messages : {
                month: {required : "Chọn tháng"},
                area_id: {required : "Chọn khu vực"},
            },
            errorPlacement: function (error, element) {
                var name = $(element).attr("name");
                error.appendTo($(element).parent());
            },
        });
        $('#btnSearch').on('click',function (e) {
            e.preventDefault();
            if(form.valid())
                table.submit();

        });
        $("select").on("select2:close", function (e) {
            $(this).valid();
        });
        $('#btnExport').on('click',function (e) {
            e.preventDefault();
            if(form.valid())
                $(this).closest('form').submit();
            return false
        });

        $('#month').datetimepicker({
           format: 'MM',
        });

        $('#year').datetimepicker({
            format: 'YYYY'
        });

        $('#area_id').on('change', function () {
            var area_id = $(this).select2('val');

            $('input[name=area_id]').val(area_id);
        });
    });
</script>
