<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC29">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>Năm</label>
                </div>
                <div class="col-md-6 type">
                    <select name="year" class="form-control select2" data-placeholder="Chọn năm">
                        <option value=""></option>
                        @for($i = 2020; $i <= date('Y'); $i++)
                            <option value="{{ $i}}">{{ $i }}</option>
                        @endfor
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
    <table id="bootstraptable" class="tDefault table table-hover table-bordered bootstrap-table" data-url="{{route('module.report_new.getData')}}">
        <thead>
            <tr class="tbl-heading">
                <th rowspan="2" data-formatter="index_formatter">STT</th>
                <th rowspan="2" data-field="subject_code">{{ trans('lacourse.course_code') }}</th>
                <th rowspan="2" data-field="subject_name">{{ trans('lacourse.course_name') }}</th>
                <th rowspan="2" data-field="training_plan_code">Mã kế hoạch</th>
                <th rowspan="2" data-field="training_plan_name">Tên kế hoạch</th>
                <th rowspan="2" data-field="course_action_1" data-align="center">Kế hoạch</th>
                <th rowspan="2" data-field="course_action_2" data-align="center">Phát sinh</th>
                <th colspan="4" data-align="center">Quý 1</th>
                <th colspan="8" data-align="center">Quý 2</th>
                <th colspan="8" data-align="center">Quý 3</th>
                <th colspan="8" data-align="center">Quý 4</th>
                <th colspan="3" data-align="center">Năm</th>
            </tr>
            <tr class="tbl-heading">
                <th data-field="plan_precious_1" data-align="center">Kế hoạch</th>
                <th data-field="perform_precious_1" data-align="center">Thực hiện</th>
                <th data-field="percent_precious_1" data-align="center">Tỷ lệ (%)</th>
                <th data-field="student_precious_1" data-align="center">Số lượt HV</th>

                <th data-field="plan_precious_2" data-align="center">Kế hoạch</th>
                <th data-field="perform_precious_2" data-align="center">Thực hiện</th>
                <th data-field="percent_precious_2" data-align="center">Tỷ lệ (%)</th>
                <th data-field="plan_accumulated_precious_2" data-align="center">Kế hoạch lũy kế</th>
                <th data-field="perform_accumulated_precious_2" data-align="center">Thực hiện lũy kế</th>
                <th data-field="percent_accumulated_precious_2" data-align="center">Tỷ lệ (%)</th>
                <th data-field="student_precious_2" data-align="center">Số lượt HV</th>
                <th data-field="student_accumulated_precious_2" data-align="center">Số lượt HV lũy kế</th>

                <th data-field="plan_precious_3" data-align="center">Kế hoạch</th>
                <th data-field="perform_precious_3" data-align="center">Thực hiện</th>
                <th data-field="percent_precious_3" data-align="center">Tỷ lệ (%)</th>
                <th data-field="plan_accumulated_precious_3" data-align="center">Kế hoạch lũy kế</th>
                <th data-field="perform_accumulated_precious_3" data-align="center">Thực hiện lũy kế</th>
                <th data-field="percent_accumulated_precious_3" data-align="center">Tỷ lệ (%)</th>
                <th data-field="student_precious_3" data-align="center">Số lượt HV</th>
                <th data-field="student_accumulated_precious_3" data-align="center">Số lượt HV lũy kế</th>

                <th data-field="plan_precious_4" data-align="center">Kế hoạch</th>
                <th data-field="perform_precious_4" data-align="center">Thực hiện</th>
                <th data-field="percent_precious_4" data-align="center">Tỷ lệ (%)</th>
                <th data-field="plan_accumulated_precious_4" data-align="center">Kế hoạch lũy kế</th>
                <th data-field="perform_accumulated_precious_4" data-align="center">Thực hiện lũy kế</th>
                <th data-field="percent_accumulated_precious_4" data-align="center">Tỷ lệ (%)</th>
                <th data-field="student_precious_4" data-align="center">Số lượt HV</th>
                <th data-field="student_accumulated_precious_4" data-align="center">Số lượt HV lũy kế</th>

                <th data-field="plan_year" data-align="center">Kế hoạch</th>
                <th data-field="perform_year" data-align="center">Thực hiện</th>
                <th data-field="percent_year" data-align="center">Tỷ lệ (%)</th>
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
                year : {required : true},
            },
            messages : {
                from_date : {required : "Chọn năm"},
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
    });
</script>
