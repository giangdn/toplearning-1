@extends('layouts.backend')

@section('page_title', trans('backend.training_process'))

@section('breadcrumb')
    <div class="ibox-content forum-container">
        @include('layouts.backend.breadcum')
    </div>
@endsection

@section('content')
    <div class="mb-4 forum-container">
        <h2 class="st_title"><i class="uil uil-apps"></i>
            <a href="{{ route('module.backend.user') }}">{{ trans('backend.user_management') }}</a>
            <i class="uil uil-angle-right"></i>
            <a href="{{ route('module.backend.user.edit',['id'=>$user_id]) }}">{{$full_name}}</a>
            <i class="uil uil-angle-right"></i>
            <span class=""> {{ trans('backend.training_process') }}</span>
        </h2>
    </div>
    <div role="main">
        @include('user::backend.layout.menu')
        <div class="table-responsive">
            <table id="dg" class="tDefault table table-hover table-bordered bootstrap-table">
                <thead>
                <tr class="tbl-heading">
                    <th width="40px" data-formatter="index_formatter">#</th>
                    <th data-field="course_code">{{ trans('backend.course_code') }}</th>
                    <th data-field="course_name">{{ trans('backend.course_name') }}</th>
                    <th data-field="course_type"  data-align="center">{{ trans('backend.training_program_form') }}</th>
                    <th data-field="training_form" data-align="center">{{ trans('backend.training_form') }}</th>        
                    <th data-field="titles_name" data-width="260px">{{ trans('backend.title') }}</th>
                    <th data-align="center" data-width="260px" data-formatter="training_date">{{ trans('app.time_held') }}</th>
                    <th data-field="score" data-align="right">{{ trans('app.score') }}</th>
                    <th data-field="result" data-width="150px" data-formatter="result" data-align="center">{{ trans('app.result') }}</th>
                    <th data-field="certificate" data-align="center" data-formatter="certificate" >{{ trans('app.certificate') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        function certificate(value, row, index) {
            if(row.image_cert) {
                return  '<a href="' + row.image_cert + '"><i class="fa fa-certificate"></i> </a>' ;
            }
            return '-';
        }
        function index_formatter(value, row, index) {
            return (index + 1);
        }
        function result(value, row, index) {
            return value == 1 ? '{{trans("backend.finish")}}' : '{{trans('backend.incomplete')}}';
        }
        function training_date(value,row,index) {
            return row.start_date +' - '+row.end_date;
        }
        var table = new LoadBootstrapTable({
            locale: '{{ \App::getLocale() }}',
            url: '{{ route('module.backend.user.trainingprocess.getdata',['user_id'=>$user_id]) }}',
        });

    </script>

@endsection
