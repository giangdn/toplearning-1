<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC03">
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
                    <label>Lựa chọn đề thi</label>
                </div>
                <div class="col-md-6 type">
                    <select class="form-control select2" name="quiz_template_id" data-placeholder="Đề thi">
                        @if($quiz_template)
                            <option value=""></option>
                            @foreach($quiz_template as $item)
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
                <th data-align="center" data-formatter="index_formatter">STT</th>
                <th data-field="quiz_name">Tên kỳ thi</th>
                <th data-field="cate_ques_name">Danh mục câu hỏi</th>
                <th data-field="num_question_used" data-align="center">SL câu hỏi được sử dụng</th>
                <th data-field="num_list_question" data-align="center">SL câu hỏi trong Ngân hàng câu hỏi</th>
                <th data-field="percent_right" data-align="center">Tỷ lệ đáp đúng</th>
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
                quiz_template_id: {required : true},
            },
            messages : {
                from_date : {required : "Chọn thời gian bắt đầu"},
                to_date : {required : "Chọn thời gian kết thúc"},
                quiz_template_id: {required : "Chọn đề thi"},
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
