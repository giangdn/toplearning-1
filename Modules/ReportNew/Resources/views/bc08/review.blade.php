<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC08">
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
                <th rowspan="2" data-align="center" data-formatter="index_formatter">STT</th>
                <th rowspan="2" data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th rowspan="2" data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th rowspan="2" data-field="lecturer">Giảng viên</th>
                <th rowspan="2" data-field="tuteurs">Trợ giảng</th>
                <th rowspan="2" data-field="training_form_name">Hình thức đào tạo</th>
                <th rowspan="2" data-field="training_type_name">Loại hình đào tạo</th>
                <th rowspan="2" data-field="level_subject">Mảng nghiệp vụ</th>
                <th rowspan="2" data-field="training_location">Địa điểm đào tạo</th>
                <th rowspan="2" data-field="training_unit">Đơn vị đào tạo</th>
                <th rowspan="2" data-field="title_join">Chức danh tham gia (bắt buộc)</th>
                <th rowspan="2" data-field="training_object">Nhóm đối tượng tham gia</th>
                <th rowspan="2" data-field="course_time">Thời lượng</th>
                <th rowspan="2" data-field="start_date">Từ ngày</th>
                <th rowspan="2" data-field="end_date">Đến ngày</th>
                <th rowspan="2" data-field="time_schedule">Thời gian</th>
                <th rowspan="2" data-field="created_by">{{ trans('lageneral.creator') }}</th>
                <th rowspan="2" data-field="registers">Số HV trong danh sách</th>
                <th colspan="3">Số HV tham dự</th>
                <th rowspan="2" data-field="students_absent">Số HV Vắng</th>
                <th rowspan="2" data-field="students_pass">Số HV đạt</th>
                <th rowspan="2" data-field="students_fail">Số HV không đạt</th>
                @if($type_cost->count() > 0)
                    @foreach($type_cost as $type)
                        @php
                            $colspan = $count_training_cost($type->id);
                        @endphp
                        <th colspan="{{ $colspan }}">{{ $type->name }}</th>
                    @endforeach
                @endif
                <th colspan="{{ ($student_cost->count() + 1) }}">CP Học viên</th>
                <th rowspan="2" data-field="total_cost">Tổng chi phí</th>
                <th rowspan="2" data-field="recruits">Tân tuyển</th>
                <th rowspan="2" data-field="exist">Hiện hữu</th>
                <th rowspan="2" data-field="plan">Kế hoạch</th>
                <th rowspan="2" data-field="incurred">Phát sinh</th>
                <th rowspan="2" data-field="monitoring_staff">Cán bộ theo dõi</th>
                <th rowspan="2" data-field="monitoring_staff_note">Ý kiến cán bộ</th>
                <th rowspan="2" data-field="teacher_note">Ý kiến giảng viên</th>
                <th rowspan="2" data-field="teacher_account_number">STK giảng viên</th>
            </tr>
            <tr>
                <th data-field="join_100" class="th-second"> 100% </th>
                <th data-field="join_75" class="th-second"> &ge;75% </th>
                <th data-field="join_below_75" class="th-second"> <75% </th>
                @foreach($training_cost as $cost)
                    <th data-field="cost_{{ $cost->id }}" class="th-second"> {{ $cost->name }}</th>
                @endforeach
                @foreach($student_cost as $student_item)
                    <th data-field="student{{ $student_item->id }}" class="th-second"> {{ $student_item->name }}</th>
                @endforeach
                <th data-field="student_total" class="th-second"> Tổng CP Học viên</th>
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
                from_date: {required : "Chọn từ ngày"},
                to_date: {required : "Chọn đến ngày"},
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
