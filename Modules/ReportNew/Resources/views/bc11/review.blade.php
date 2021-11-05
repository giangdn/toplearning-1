<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC11">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.date_from') }}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="from_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.date_to') }}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="to_date" class="form-control datepicker-date">
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
                <th data-field="user_code">Mã nhân viên</th>
                <th data-field="fullname">Họ và tên</th>
                <th data-field="role_lecturer">Vai trò giảng viên</th>
                <th data-field="role_tuteurs">Vai trò trợ giảng</th>
                <th data-field="account_number">Số tài khoản</th>
                <th data-field="area_name_unit">Khu vực</th>
                <th data-field="unit_name_1">Đơn vị trực tiếp</th>
                <th data-field="unit_name_2">Đơn vị quản lý</th>
                {{-- <th data-field="unit_code_1">Mã đơn vị cấp 1</th>
                <th data-field="unit_name_1">Đơn vị cấp 1</th>
                <th data-field="unit_code_2">Mã đơn vị cấp 2</th>
                <th data-field="unit_name_2">Đơn vị cấp 2</th>
                <th data-field="unit_code_3">Mã đơn vị cấp 3</th>
                <th data-field="unit_name_3">Đơn vị cấp 3</th> --}}
                <th data-field="title_name">Chức danh</th>
                <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th data-field="training_form_name">Hình thức đào tạo</th>
                <th data-field="course_time">Thời lượng khóa học</th>
                <th data-field="time_lecturer">Thời lượng dạy chính (giờ)</th>
                <th data-field="time_tuteurs">Thời lượng trợ giảng (giờ)</th>
                <th data-field="start_date">Từ ngày</th>
                <th data-field="end_date">Đến ngày</th>
                <th data-field="time_schedule">Thời gian</th>
                <th data-field="training_location_name">Địa điểm đào tạo</th>
                {{--<th data-field="cost">Chi phí giảng dạy</th>--}}
                @foreach($training_cost as $cost)
                    <th data-field="training_cost{{ $cost->id }}">{{ $cost->name }}</th>
                @endforeach
                <th data-field="total_cost">Tổng chi phí</th>
                <th data-field="teacher">Kết quả đánh giá (%)</th>
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
