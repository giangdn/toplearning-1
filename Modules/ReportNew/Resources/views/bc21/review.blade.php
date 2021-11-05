<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC21">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
        </div>
        <div class="col-md-12 text-center">
            <button  id="btnSearch" class="btn btn-primary">{{trans('backend.view_report')}}</button>
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
            <th data-field="code">{{ trans('lacourse.course_code') }}</th>
            <th data-field="name">{{ trans('lacourse.course_name') }}</th>
            <th data-field="start_date_format">Từ ngày</th>
            <th data-field="end_date_format">Đến ngày</th>
            <th data-field="created_user">Người tao</th>
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
            },
            messages : {
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
