<form name="frm" action="{{route('module.report_new.export')}}" id="form-search" method="post" autocomplete="off">
    @csrf

    <input type="hidden" name="report" value="BC15">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-7">
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>Ngày vào làm {{ trans('backend.date_from') }}</label>
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

            <div class="form-group row">
                <div class="col-md-4 control-label required">
                    <label>Chức danh <span class="text-danger">*</span></label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-title" name="title" id="title_id" data-placeholder="Chức danh">
                        <option value=""></option>
                    </select>
                    <input type="hidden" name="title_id" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">
                    <label>Trạng thái</label>
                </div>
                <div class="col-md-8 type">
                    <select class="form-control load-status-profile" name="status_id" id="status_id" data-placeholder="Trạng thái">
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
    </table>
</div>
{{--<th data-align="center" data-formatter="index_formatter">STT</th>--}}
{{--<th  data-field="profile_code">Mã nhân viên</th>--}}
{{--<th data-field="full_name">Họ và tên</th>--}}
{{--<th data-field="email">Email</th>--}}
{{--<th data-field="phone">Điện thoại</th>--}}
{{--<th data-field="area">Khu vực</th>--}}
{{--<th data-field="unit3_code">Mã đơn vị cấp 3</th>--}}
{{--<th data-field="unit3_name">Đơn vị cấp 3</th>--}}
{{--<th data-field="unit2_code">Mã đơn vị cấp 2</th>--}}
{{--<th data-field="unit2_name">Đơn vị cấp 2</th>--}}
{{--<th data-field="unit1_code">Mã đơn vị cấp 1</th>--}}
{{--<th data-field="unit1_name">Đơn vị cấp 1</th>--}}
{{--<th data-field="position_name">Chức vụ</th>--}}
{{--<th data-field="title_name">Chức danh</th>--}}
{{--<th data-field="join_company">Ngày vào làm</th>--}}
{{--<th data-field="status_user">Tình trạng</th>--}}
<script type="text/javascript">
    function index_formatter(value, row, index) {
        return (index + 1);
    }

    $(document).ready(function () {
        var columns = [

        ];
        // var table = new BootstrapTable({
        //     url: $('#bootstraptable').data('url'),
        //     sort_name:'user_id',
        //     columns
        // });
        var form = $('#form-search');
        form.validate({
            ignore: [],
            rules : {
                title: {required : true},
            },
            messages : {
                title: {required : "Chọn chức danh"},
            },
            errorPlacement: function (error, element) {
                var name = $(element).attr("name");
                error.appendTo($(element).parent());
            },
        });
        $('#btnSearch').on('click',function (e) {
            e.preventDefault();
            if(form.valid()){
                // $('.bootstrap-table').bootstrapTable('destroy');
                $('#bootstraptable').bootstrapTable('destroy');
                $('#bootstraptable').find("tbody").empty();
                $('#bootstraptable').find("thead").empty();
                var title = $('#title_id').val();
                $.ajax({
                    url: '{{route("module.report_new.filter")}}',
                    type: 'POST',
                    data: {'title_id':title,'type':'SubjectByTitle'},
                    dataType: 'json',
                    success: function (data) {
                        columns = [];
                        columns.push({field:'profile_code',title:'Mã nhân viên',sortable:false});
                        columns.push({field:'full_name',title:'Họ tên',sortable:false});
                        columns.push({field:'email',title:'Email',sortable:false});
                        columns.push({field:'phone',title:'Điện thoại',sortable:false});
                        columns.push({field:'area',title:'Khu vực',sortable:false});
                        // columns.push({field:'unit1_code',title:'Mã đơn vị cấp 1',sortable:false});
                        columns.push({field:'unit1_name',title:'Đơn vị trực tiếp',sortable:false});
                        // columns.push({field:'unit2_code',title:'Mã đơn vị cấp 2',sortable:false});
                        columns.push({field:'unit2_name',title:'Đơn vị quản lý',sortable:false});
                        // columns.push({field:'unit3_code',title:'Mã đơn vị cấp 3',sortable:false});
                        // columns.push({field:'unit3_name',title:'Đơn vị cấp 3',sortable:false});
                        columns.push({field:'unit_type',title:'Loại ĐV',sortable:false});
                        columns.push({field:'position',title:'Chức vụ',sortable:false});
                        columns.push({field:'title',title:'Chức danh',sortable:false});
                        columns.push({field:'join_date',title:'Ngày vào làm',sortable:false});
                        columns.push({field:'status',title:'Tình trạng',sortable:false});

                        data.forEach(function(item, index) {
                            // console.log(index);
                            columns.push({
                                field: 'subject'+item.code,
                                title: item.name,
                                sortable: false,
                            });
                        });
                        console.log(columns);


                        $('#bootstraptable').bootstrapTable({
                            url: $('#bootstraptable').data('url'),
                            locale: 'vi-VN',
                            sidePagination: 'server',
                            pagination: true,
                            sortName: 'user_id',
                            sortOrder: 'desc',
                            toggle: 'table',
                            search: this.search,
                            pageSize: 20,
                            idField: 'id',
                            cache: false,
                            columns:columns,
                            queryParams: function (params) {
                                let field_search = $('#form-search').serializeArray();
                                $.each(field_search, function (i, item) {
                                    params[item.name] = item.value;
                                });
                                return params;
                            }
                        });
                    }
                });
                // columns.push(
                //     {
                //     field: 'subject1',
                //     title: 'test1',
                //     sortable: false,
                //     }
                // );
                // columns.push(
                //     {
                //         field: 'subject2',
                //         title: 'test2',
                //         sortable: false,
                //     }
                // );



            }

                // table.submit();

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
