@extends('layouts.backend')

@section('header')
    <link rel="stylesheet" href="{{ asset('styles/module/report/css/list.css') }}">
@endsection

@section('page_title', 'Xem báo cáo')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title">
            <i class="uil uil-apps"></i>
            <a href="{{route('module.report_new')}}">Báo cáo mới</a>
            <i class="uil uil-angle-right"></i>
            <span>Lịch sử tải</span>
        </h2>
    </div>
    <div role="main" id="report" class="mt-4">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="table-responsive">
                    <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
                        <thead>
                        <tr class="tbl-heading">
                            <th data-formatter="index_formatter" data-align="center" data-width="3%">#</th>
                            <th data-field="report_name" data-align="left">Báo cáo</th>
                            <th data-field="created_at2" data-align="center">Ngày tải</th>
                            <th data-field="status" data-formatter="download_formatter" data-align="center">Download</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var table = new LoadBootstrapTable({
            url: "{{ route('module.report_new.getDataHistoryExport') }}",
        });

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function download_formatter(value, row, index) {
           /* return row.size > 0 ? '<a href="'+row.download+'"><i class="fa fa-download"></i> Download ('+ row.size +' MB)</a>' : 'Chờ xử lý ....';*/
            switch (value) {
                case '0': return 'Lỗi xuất báo cáo: ' + row.error;
                case '1': return '<a href="'+ row.download +'"><i class="fa fa-download"></i> Download ('+ row.size +' MB)</a>';
                case '2': return 'Chờ xử lý ....';
                default : return 'Đang xuất báo cáo...';
            }
        }

    </script>
@stop
