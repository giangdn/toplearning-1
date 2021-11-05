{{-- @extends('layouts.backend')

@section('page_title', 'Lịch sử truy cập')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Lịch sử truy cập khóa học</span>
        </h2>
    </div>
@endsection

@section('content') --}}
<div role="main" id="report" class="pt-2">
    <form name="frm" action="" id="form-search" method="post" autocomplete="off">
        @csrf
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-7">
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>Khóa học</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="course" class="form-control" data-placeholder="Mã hoặc tên khóa học">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>Tên nhân viên</label>
                    </div>
                    <div class="col-md-6">
                        <select  name="user" class="form-control load-user" data-placeholder="Nhân viên"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{trans('backend.date_from')}} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="from_date" class="form-control datepicker-date">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3 control-label">
                        <label>{{trans('backend.date_to')}} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="to_date" class="form-control datepicker-date">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <button type="submit" id="btnSearch" class="btn btn-primary">Truy vấn</button>
                    </div>
                </div>

            </div>
        </div>
    </form>
    <br>
    <div class="table-responsive">
        <table id="table-history-login" class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr class="tbl-heading">
                    <th data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                    <th data-field="course_code">Mã</th>
                    <th data-field="course_name">Khóa học</th>
                    <th data-field="course_type" data-formatter="course_type_formatter">Loại khóa</th>
                    <th data-field="user_code">MSNV</th>
                    <th data-field="user_name">{{trans('backend.student')}}</th>
                    <th data-field="start_date" data-align="center" data-width="15%">{{trans("backend.time_start")}}</th>
                    <th data-field="end_date" data-align="center" data-width="15%">{{trans("backend.last_access")}}</th>
                </tr>
            </thead>
        </table>
    </div>
    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        }
        function course_type_formatter(value, row, index) {
            return (value==1)?'online':'Tập trung';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.log.view.course.index') }}',
            table: '#table-history-login'
        });
        $(document).ready(function() {
            $(".datepicker-date").datepicker({
                format: "dd/mm/yyyy",
                minViewMode: 0
            });
            var form = $('#form-search');
            form.validate({
                ignore: [],
                rules : {
                    from_date : {required : true},
                    to_date : {required : true},
                },
                messages : {
                    from_date : {required : "Chọn thời gian bắt đầu"},
                    to_date : {required : "Chọn thời gian truy cập cuối"},
                },
                errorPlacement: function (error, element) {
                    var name = $(element).attr("name");
                    error.appendTo($(element).parent());
                },
            });
            // $('#btnSearch').on('click',function (e) {
            //     e.preventDefault();
            //     if(form.valid())
            //         table.submit();

            // });
        });
    </script>
{{-- @endsection --}}
