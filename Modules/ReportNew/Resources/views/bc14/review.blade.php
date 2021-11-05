<form name="frm" action="" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC14">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-group row">
                <div class="col-md-3 control-label">
                    <label>Danh mục</label>
                </div>
                <div class="col-md-9">
                    <select name="name_obj" id="name_obj" class="form-control select2" data-placeholder="Danh mục">
                        <option value=""></option>
                        @foreach($obj_arr as $key => $obj)
                            <option value="{{ $key }}" {{ $name_obj == $key ? 'selected' : '' }}> {{ $obj }}</option>
                        @endforeach
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
        @if($name_obj)
            @include('reportnew::bc14.'.$name_obj)
        @endif
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
                name_obj: {required : true},
            },
            messages : {
                name_obj: {required : "Chọn Danh mục"},
            },
            errorPlacement: function (error, element) {
                var name = $(element).attr("name");
                error.appendTo($(element).parent());
            },
        });
        $('#btnSearch').on('click',function (e) {
            e.preventDefault();
            if(form.valid()){
                $(this).closest('form').attr('action', '{{ route('module.report_new.review', ['id' => 'BC14']) }}').submit();
            }
        });
        $("select").on("select2:close", function (e) {
            $(this).valid();
        });
        $('#btnExport').on('click',function (e) {
            e.preventDefault();
            if(form.valid())
                $(this).closest('form').attr('action', '{{ route('module.report_new.export') }}').submit();
            return false
        });
    });
</script>
