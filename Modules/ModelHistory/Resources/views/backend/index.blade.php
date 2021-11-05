{{-- @extends('layouts.backend')

@section('page_title', 'Lịch sử cập nhật')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Lịch sử cập nhật</span>
        </h2>
    </div>
@endsection

@section('content') --}}

    <div role="main">
        <form name="frm" action="{{route('module.report.export')}}" id="form-search" method="post" autocomplete="off">
            @csrf
            <input type="hidden" name="report" value="BC18">
            <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-7">
                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label>Chọn chức năng</label>
                        </div>
                        <div class="col-md-6">
                            <select name="model" class="form-control load-table" data-placeholder="-- Chọn chức năng --"></select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3 control-label">
                            <label>Tên nhân viên</label>
                        </div>
                        <div class="col-md-6">
                            <select name="user" class="form-control load-user" data-placeholder="-- Nhân viên --"></select>
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

        <table class="tDefault table table-hover bootstrap-table">
            <thead>
                <tr>
                    <th data-align="center" data-formatter="stt_formatter" data-width="50px">STT</th>
                    <th data-field="model_id">model id</th>
                    <th data-field="action" >Thao tác</th>
                    <th data-field="note" >Ghi chú</th>
                    <th data-field="created_name">{{ trans('lageneral.creator') }}</th>
                    <th data-field="created_date"  data-align="center">Thời gian</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function stt_formatter(value, row, index) {
            return (index + 1);
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.modelhistory.index') }}',
        });
        $(document).ready(function () {
            $(".datepicker-date").datepicker({
                format: "dd/mm/yyyy",
                minViewMode: 0
            });
        });
    </script> 
{{-- @endsection --}}
