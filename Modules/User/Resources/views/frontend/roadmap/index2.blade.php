@extends('layouts.app')

@section('page_title', 'Chương trình khung')

@section('breadcrumb')
    <li class="breadcrumb-item"><span tabindex="0">{{trans('backend.trainingroadmap')}}</span></li>
@endsection

@section('content')
    <div class="container-fluid" id="user-info">
        <ol class="breadcrumb" style="background: white;margin-bottom: 0;">
            <li><a href="/"><i class="glyphicon glyphicon-home"></i> &nbsp;Trang chủ</a></li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">&raquo;</li>
            <li><span><a href="{{route('module.frontend.user.info')}}">Thông tin học viên</a></span></li>
            <li style="padding-left: 5px; color: #717171; padding-right: 5px; font-weight: 700;">&raquo;</li>
            <li><span>{{trans('backend.trainingroadmap')}}</span></li>
        </ol>
        @include('user::frontend.layout.menu')
        <div class="table-responsive">
            <table class="tDefault table table-hover bootstrap-table">
            <thead>
            <tr class="tbl-heading">
                <th data-field="index" data-formatter="index_formatter" width="40px;" rowspan="2"
                    style="vertical-align: middle;">#
                </th>
                <th data-field="subject_code" rowspan="2" style="vertical-align: middle;">Mã học phần </th>
                <th data-field="subject_name" rowspan="2" data-formatter="subject_name_formatter">Tên học phần</th>
{{--                <th data-field="course">Tên khóa học</th>--}}
                <th rowspan="2" data-field="training_unit" style="vertical-align: middle;">{{trans('backend.training_units')}}</th>
                <th rowspan="2" data-field="training_form" data-align="center" data-formatter="course_type" style="vertical-align: middle;">{{trans('backend.training_program_form')}}</th>
                <th style="vertical-align: middle;text-align: center;" colspan="2" data-align="center">Thời gian tổ chức</th>
                <th colspan="2" style="text-align: center; vertical-align: middle;">{{trans('backend.required_time_complete_course')}}</th>
                <th rowspan="2" data-align="center" data-field="finish_date">Thời gian hoàn thành khóa học</th>
                <th rowspan="2" data-field="score">{{ trans('backend.score') }}</th>
            </tr>
            <tr class="tbl-heading">
                <th data-field="start_date" data-align="center">{{trans('backend.date_from')}}</th>
                <th data-field="end_date" data-align="center">{{trans('backend.date_to')}}</th>
                <th data-field="start_date" data-align="center">{{trans('backend.date_from')}}</th>
                <th data-field="required_finish_date" data-align="center">{{trans('backend.date_to')}}</th>
            </tr>
            </thead>
        </table>
        </div>
    </div>

    <script type="text/javascript">
        // function plan_detail_formatter(value, row, index) {
        //     return '<a href="'+ row.plan_url +'">' + '<i class="fa fa-certificate"></i>' + '</a>';
        // }
        function index_formatter(value, row, index) {
            return (index + 1);
        };
        function course_type(value, row, index) {
            if (value==1)
                return trans('backend.online');
            else if(value==2)
                return trans('backend.offline');
            return '-';
        }

        function subject_name_formatter(value, row, index) {
            return '<a href="javascript:void(0)" class="modal-roadmap" data-id="'+row.id+'">'+ row.subject_name +'</a>'
        }

        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.frontend.user.roadmap.getDataRoadmap') }}',
        });

        $('#user-info').on('click', '.modal-roadmap', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('module.frontend.user.modal_content') }}',
                dataType: 'html',
                data: {
                    'roadmap_id': $(this).data('id')
                },
            }).done(function(data) {
                $("#app-modal").html(data);
                $("#app-modal #modal-content").modal();
                return false;
            }).fail(function(data) {

                Swal.fire(
                    '',
                    '{{ trans('lageneral.data_error ') }}',
                    'error'
                );
                return false;
            });
        });

    </script>

@endsection
