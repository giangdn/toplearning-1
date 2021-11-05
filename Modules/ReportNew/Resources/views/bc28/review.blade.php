<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC28">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_from')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="from_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{trans('backend.date_to')}}</label>
                </div>
                <div class="col-md-6">
                    <input type="text" name="to_date" class="form-control datepicker-date">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>{{ trans('backend.quiz') }}</label>
                </div>
                <div class="col-md-6 type">
                    <select name="quiz_id" class="form-control select2" data-placeholder="-- {{ trans('backend.quiz') }} --">
                        <option value=""></option>
                        @if($quiz)
                            @foreach($quiz as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        @endif
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
                <th rowspan="2" data-field="quiz_name">Tên kỳ thi</th>
                <th rowspan="2" data-field="type_name">Loại hình thi</th>
                <th rowspan="2" data-field="user_code">MSNV</th>
                <th rowspan="2" data-field="full_name">Họ và tên</th>
                <th rowspan="2" data-field="title_name">Chức danh</th>
                <th rowspan="2" data-field="unit_name">Đơn vị trực tiếp</th>
                <th rowspan="2" data-field="unit_parent_name">Đơn vị quản lý</th>
                <th rowspan="2" data-field="area_name">Khu vực</th>
                <th rowspan="2" data-field="email">Email</th>
                <th rowspan="2" data-field="status" data-align="center">Trạng thái</th>
                <th rowspan="2" data-field="start_date" data-align="center">Bắt đầu lúc</th>
                <th rowspan="2" data-field="end_date" data-align="center">Được hoàn thành</th>
                <th rowspan="2" data-field="execution_time" data-align="center">Thời gian thực hiện</th>
                <th rowspan="2" data-field="score" data-align="center">Điểm</th>
                <th colspan="2" data-align="center">Số lượng câu hỏi</th>
                <th colspan="2" data-align="center">Tỉ lệ</th>
            </tr>
            <tr class="tbl-heading">
                <th data-field="num_true" data-align="center">Đúng</th>
                <th data-field="num_false" data-align="center">Sai</th>
                <th data-field="percent_true" data-align="center">% Đúng</th>
                <th data-field="percent_false" data-align="center">% Sai</th>
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
                from_date : {required : true},
                to_date : {required : true},
                quiz_id : {required : true},
            },
            messages : {
                from_date : {required : "Chọn thời gian bắt đầu"},
                to_date : {required : "Chọn thời gian kết thúc"},
                quiz_id : {required : "Chọn kì thi"},
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
