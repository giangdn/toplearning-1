<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf
    <input type="hidden" name="report" value="BC30">
    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>Hình thức đào tạo</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control select2" name="course_type" id="course_type" data-placeholder="Hình thức đào tạo" multiple>
                        <option value=""></option>
                        <option value="1">Trực tuyến</option>
                        <option value="2">Tập trung</option>
                    </select>
                    <input type="hidden" name="course_type" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>Chuyên đề đào tạo </label>
                </div>
                <div class="col-md-8">
                    <select class="form-control load-subject" id="subject_id" data-course_type="" data-placeholder="Chuyên đề đào tạo" multiple>
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="subject_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_from') }}</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="from_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>{{ trans('backend.date_to') }}</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="to_date" class="form-control datepicker-date">
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" id="btnSearch" class="btn btn-primary">{{ trans('backend.view_report') }}</button>
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
                <th data-field="course_code">{{ trans('lacourse.course_code') }}</th>
                <th data-field="course_name">{{ trans('lacourse.course_name') }}</th>
                <th data-field="start_date" data-align="center">Thời gian bắt đầu</th>
                <th data-field="end_date" data-align="center">Thời gian kết thúc</th>
                <th data-field="quality_course" data-align="center">Chất lượng chung khóa học (%)</th>
                <th data-field="program_content" data-align="center">Nội dung chương trình (%)</th>
                <th data-field="teacher" data-align="center">Giảng viên (%)</th>
                <th data-field="organization" data-align="center">Tổ chức (%)</th>
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
                course_type: {required : true},
            },
            messages : {
                course_type: {required : "Chọn hình thức đào tạo"},
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

        $('#subject_id').on('change', function () {
            var subject_id = $(this).select2('val');

           $('input[name=subject_id]').val(subject_id);
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

        $('#course_type').on('change', function () {
            var course_type = $(this).select2('val');
            $('input[name=course_type]').val(course_type);

            $("#subject_id").empty();
            $('#subject_id').data('course_type', course_type);
            $('#subject_id').trigger('change');
        });
    });
</script>
