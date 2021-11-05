<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC26">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.date_from') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="from_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.date_to') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="to_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>Giảng viên</label>
                </div>
                <div class="col-md-9">
                    <select name="user_id" class="form-control load-user" data-placeholder="Giảng viên">
                        <option value=""></option>
                    </select>
                </div>
            </div>
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
                <th data-formatter="course_formatter">Chuyên đề</th>
                <th data-formatter="course_date_formatter">Thời gian đào tạo</th>
                <th data-field="course_time">Thời lượng</th>
                <th data-field="user_code">MSNV</th>
                <th data-field="fullname">Tên Giảng viên</th>
                <th data-field="area_name_unit">Khu vực</th>
                <th data-field="unit_name_1">Đơn vị trực tiếp</th>
                <th data-field="unit_name_2">Đơn vị quản lý</th>
                <th data-field="account_number">Số tài khoản</th>
                <th data-field="cost">Số tiền</th>
            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

    function course_formatter(value, row, index) {
        return row.course_name +' ('+ row.course_code +')' ;
    }

    function course_date_formatter(value, row, index) {
        return row.start_date +' <i class="fa fa-arrow-right"></i> '+ row.end_date;
    }

    $(document).ready(function () {
        var table = new BootstrapTable({
            url: $('#bootstraptable').data('url'),
        });
        var form = $('#form-search');
        form.validate({
            ignore: [],
            rules : {
                from_date: {required : true},
                to_date: {required : true},
            },
            messages : {
                from_date: {required : "Chọn ngày bắt đầu"},
                to_date: {required : "Chọn ngày kết thúc"},
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
