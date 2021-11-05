<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC12">
    <div class="row">
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
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>Khu vực</label>
                </div>
                <div class="col-md-9 type">
                    <select class="form-control load-area" data-level="3" name="training_area_id" data-placeholder="Khu vực">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>Loại hình đào tạo</label>
                </div>
                <div class="col-md-9 type">
                    <select class="form-control load-training-form" id="training_type_id" data-placeholder="Loại hình đào tạo" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="training_type_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>Chức danh</label>
                </div>
                <div class="col-md-9 type">
                    <select class="form-control load-title" id="title_id" data-placeholder="Chức danh" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="title_id" value="">
                </div>
            </div>
        </div>
        <div class="col-md-6">
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
                <th data-field="user_code">Mã nhân viên</th>
                <th data-field="fullname">Họ và tên</th>
                <th data-field="email">Email</th>
                <th data-field="area_name_unit">Khu vực</th>
                <th data-field="phone">Điện thoại</th>
                <th data-field="unit_name_1">Đơn vị trực tiếp</th>
                <th data-field="unit_name_2">Đơn vị quản lý</th>
                <th data-field="unit_type_name">Loại đơn vị</th>
                <th data-field="position_name">Chức vụ</th>
                <th data-field="title_name">Chức danh</th>
                <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th data-field="training_unit">Đơn vị đào tạo</th>
                <th data-field="training_form_name">Hình thức đào tạo</th>
                <th data-field="course_time">Thời lượng khóa học</th>
                <th data-field="start_date">Từ ngày</th>
                <th data-field="end_date">Đến ngày</th>
                <th data-field="time_schedule">Thời gian</th>
                <th data-field="attendance">Tổng thời lượng tham gia</th>
                <th data-field="status_user">Trạng thái</th>
                <th data-field="score">Điểm</th>
                <th data-field="result">Kết quả</th>
                @foreach($student_cost as $student)
                    <th data-field="student_cost{{ $student->id }}"> {{ $student->name }}</th>
                @endforeach
                <th data-field="avg_teacher_cost">Bình quân CPGV</th>
                <th data-field="avg_organizational_costs">Bình quân CPTC</th>
                <th data-field="avg_academy_cost">Bình quân CP Học viện</th>
                <th data-field="total_cost">Tổng CP</th>
                <th data-field="note">Ghi chú</th>
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

        $('#training_type_id').on('change', function () {
            var training_type_id = $(this).select2('val');

            $('input[name=training_type_id]').val(training_type_id);
        });

        $('#title_id').on('change', function () {
            var title_id = $(this).select2('val');

            $('input[name=title_id]').val(title_id);
        });
    });
</script>
