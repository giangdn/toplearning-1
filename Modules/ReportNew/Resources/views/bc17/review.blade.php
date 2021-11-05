<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC17">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-4 control-label required">
                    <label>{{ trans('backend.date_from') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="from_date" value="{{date('d/m/Y')}}" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label required">
                    <label>{{ trans('backend.date_to') }} <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="to_date" value="{{date('t/m/Y')}}" class="form-control datepicker-date">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-4 control-label ">
                    <label>Chức danh </label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-title" name="title_id" id="title_id" data-placeholder="Chức danh">
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="title_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>Đơn vị</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-unit" name="unit_id" id="unit_id" data-placeholder="Đơn vị">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>Khu vực</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-area" name="area_id" id="area_id" data-placeholder="Khu vực">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>Loại hình đào tạo</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-training-type" name="training_type_id" id="training_type_id" data-placeholder="Loại hình đào tạo">
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
            <th data-field="user_code">Mã nhân viên</th>
            <th data-field="full_name">Họ và tên</th>
            <th data-field="email">Email</th>
            <th data-field="phone">Điện thoại</th>
            <th data-field="area">Khu vực</th>
            <th data-field="unit1_name">Đơn vị trực tiếp</th>
            <th data-field="unit2_name">Đơn vị quản lý</th>
            {{-- <th data-field="unit1_code">Mã đơn vị cấp 1</th>
            <th data-field="unit1_name">Đơn vị cấp 1</th>
            <th data-field="unit2_code">Mã đơn vị cấp 2</th>
            <th data-field="unit2_name">Đơn vị cấp 2</th>
            <th data-field="unit3_code">Mã đơn vị cấp 3</th>
            <th data-field="unit3_name">Đơn vị cấp 3</th> --}}
            <th data-field="position_name">Chức vụ</th>
            <th data-field="titles_name">Chức danh</th>
            <th data-field="training_program_name">Tên chủ đề</th>
            <th data-field="subject_name">Tên chuyên đề</th>
            <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
            <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
            <th data-field="training_unit">Đơn vị đào tạo</th>
            <th data-field="training_type">Hình thức đào tạo</th>
            <th data-field="training_address">Địa điểm đào tạo</th>
            <th data-field="course_time">Thời lượng khóa</th>
            <th data-field="start_date">Từ ngày</th>
            <th data-field="end_date">Đến ngày</th>
            <th data-field="time_schedule">Thời gian</th>
            <th data-field="cost_held">Bình quân chi phí tổ chức</th>
            <th data-field="cost_training">Bình quân chi phí phòng đào tạo</th>
            <th data-field="cost_external">Bình quân chi phí bên ngoài</th>
            <th data-field="cost_teacher">Bình quân chi phí giảng viên</th>
            <th data-field="cost_student">Chi phí học viên</th>
            <th data-field="cost_total">Tổng chi phí</th>
            <th data-field="time_commit">Số ngày cam kết</th>
            <th data-field="time_commit_formatter">Thời gian cam kết</th>
            <th data-field="time_rest">Thời hạn còn</th>
            <th data-field="cost_refund">Chi phí bồi hoàn (đồng)</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }
    $(document).ready(function () {

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
            if(form.valid()){
                var table = new BootstrapTable({
                    url: $('#bootstraptable').data('url'),
                });
                // table.submit();
            }

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

        $('#title_id').on('change', function () {
            var title_id = $(this).select2('val');

            $('input[name=title_id]').val(title_id);
        });
    });
</script>
