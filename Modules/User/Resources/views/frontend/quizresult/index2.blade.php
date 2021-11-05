@extends('layouts.app')

@section('page_title', 'Kết quả thi')

@section('breadcrumb')
    <div class="ibox-content forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <span class="font-weight-bold">Kết quả thi</span>
        </h2>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb" style="background: white;margin-bottom: 0;">
            <li><a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;Trang chủ</a></li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">&raquo;</li>
            <li><span><a href="{{route('module.frontend.user.info')}}">Thông tin học viên</a></span></li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">&raquo;</li>
            <li><span>Kết quả thi</span></li>
        </ol>
        @include('user::frontend.layout.menu')
        <div class="table-responsive">
            <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
                <thead>
                <tr class="tbl-heading">
                    <th data-width="40px" data-formatter="index_formatter">#</th>
                    <th data-field="code" data-width="80px">{{trans('backend.quiz_code')}}</th>
                    <th data-field="name">{{ trans('backend.exam') }}</th>
                    <th  data-field="start_date" data-width="180px" data-align="center">{{trans('backend.start_date')}}</th>
                    <th  data-field="end_date" data-width="180px" data-align="center">{{trans('backend.end_date')}}</th>
                    <th  data-field="limit_time" data-width="150px" data-align="center">Thời gian thi (phút)</th>
                    <th  data-align="center" data-width="80px"  data-field="grade">{{ trans('backend.score') }}</th>
                    <th  data-align="center" data-width="160px" data-field="result" data-formatter="result_formatter">{{ trans('backend.result') }}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        function index_formatter(value, row, index) {
            return (index + 1);
        };
        function result_formatter(value, row, index) {
            return value == 1 ? '{{trans("backend.finish")}}' : 'Chưa hoàn thành';
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.frontend.user.quizresult.getData') }}',
        });

    </script>

@endsection
