@extends('layouts.app')

@section('page_title', 'Quá trình đào tạo')

@section('breadcrumb')
    <li class="breadcrumb-item"><span tabindex="0">Quá trình đào tạo</span></li>
@endsection

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb" style="background: white;margin-bottom: 0;">
            <li><a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;Trang chủ</a></li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">&raquo;</li>
            <li><span><a href="{{route('module.frontend.user.info')}}">Thông tin học viên</a></span></li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">&raquo;</li>
            <li><span>Quá trình đào tạo</span></li>
        </ol>
        @include('user::frontend.layout.menu')
        <div class="table-responsive">
            <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
                <thead>
                <tr class="tbl-heading">
                    <th width="5%" data-formatter="index_formatter">#</th>
                    <th data-field="code">{{ trans('backend.course_code') }}</th>
                    <th data-field="course_name">{{ trans('backend.course') }}</th>
                    <th  data-field="training_unit">{{trans('backend.training_units')}}</th>
                    <th  data-field="course_type" data-formatter="course_type" data-align="center">{{trans('backend.training_program_form')}}</th>
                    <th  data-align="center" data-width="200px" data-formatter="training_date">Thời gian tổ chức</th>
                    <th  data-field="score" data-align="right">{{ trans('backend.score') }}</th>
                    <th  data-field="result" data-width="150px" data-formatter="result" data-align="center">{{ trans('backend.result') }}</th>
                    <th  data-field="cert_code" data-align="center" data-formatter="certificate" >{{trans("backend.certificates")}}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        function certificate(value, row, index) {
            return row.result == 1 ? '<a href="'+row.image_cert+'" ><i class="fa fa-certificate"></i></a>':'-';
        }
        function index_formatter(value, row, index) {
            return (index + 1);
        }
        function course_type(value, row, index) {
            return value == 1 ? trans('backend.online') : trans('backend.offline');
        }
        function result(value, row, index) {
            return value == 1 ? '{{trans("backend.finish")}}' : 'Chưa hoàn thành';
        }
        function training_date(value,row,index) {
            return row.start_date +' - '+row.end_date;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.frontend.user.trainingprocess.getData') }}',
        });

    </script>

@endsection
